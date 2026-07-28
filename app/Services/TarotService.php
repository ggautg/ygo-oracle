<?php

namespace App\Services;

use App\Models\YgoCard;
use App\Models\OracleRace;
use App\Models\OracleAttribute;
use App\Models\OracleNumber;
use Illuminate\Support\Collection;

class TarotService
{
    private const POSITIONS = ['Pasado', 'Presente', 'Futuro'];

    public function drawSpread(int $count = 3, ?string $question = null): array
    {
        $seed = $this->ritualSeed($question);
        $cards = $this->drawCardsFromSeed($count, $seed);

        $spread = $cards->values()->map(fn ($card, $i) => [
            'position' => self::POSITIONS[$i] ?? null,
            'name' => $card->name,
            'race' => $card->race,
            'attribute' => $card->attribute,
            'level' => $card->level,
            'reading' => $this->renderCard($card),
        ]);

        return [
            'cards' => $spread,
            'coincidences' => $this->findCoincidences($cards),
            'numerology' => $this->numerologyReading($cards),
            'mystic_message' => $this->mysticMessage($cards),
        ];
    }

    private function ritualSeed(?string $question = null): string
    {
        $entropy = implode('|', [
            bin2hex(random_bytes(32)),
            microtime(true),
            memory_get_usage(),
            $question ?? '',
        ]);

        return hash('sha256', $entropy);
    }

    private function drawCardsFromSeed(int $count, string $seed): Collection
    {
        $total = YgoCard::count();
        $chunks = str_split($seed, 8);

        return collect(range(0, $count - 1))->map(function ($i) use ($chunks, $total) {
            $chunkHex = $chunks[$i] ?? bin2hex(random_bytes(4));
            $offset = hexdec($chunkHex) % $total;
            return YgoCard::skip($offset)->take(1)->first();
        });
    }

    private function renderCard(YgoCard $card): string
    {
        $parts = array_filter([
            $this->raceEssence($card->race),
            $this->attributeEssence($card->attribute),
        ]);

        return implode(' ', $parts);
    }

    private function raceEssence(?string $race): ?string
    {
        return OracleRace::where('race', $race)->value('essence');
    }

    private function attributeEssence(?string $attribute): ?string
    {
        return OracleAttribute::where('attribute', $attribute)->value('essence');
    }

    private function findCoincidences(Collection $cards): array
    {
        $out = [];
        $races = $cards->pluck('race')->filter();
        if ($races->count() >= 2 && $races->unique()->count() === 1) {
            $out[] = "Las cartas comparten la raza {$races->first()}.";
        }
        $attributes = $cards->pluck('attribute')->filter();
        if ($attributes->count() >= 2 && $attributes->unique()->count() === 1) {
            $out[] = "Las cartas comparten el atributo {$attributes->first()}.";
        }
        return $out;
    }

    private function numerologyReading(Collection $cards): array
    {
        $total = $cards->sum('level');
        $digit = $this->reduceToDigit($total);
        $meaning = OracleNumber::where('number', $digit)->value('meaning');

        return ['total' => $total, 'digit' => $digit, 'meaning' => $meaning];
    }

    private function reduceToDigit(int $n): int
    {
        while ($n > 9) {
            $n = array_sum(str_split((string) $n));
        }
        return $n;
    }

    private function mysticMessage(Collection $cards): string
    {
        return $cards
            ->map(fn ($card) => $this->extractRandomWord($card->description))
            ->shuffle()
            ->implode(' · ');
    }

    private function extractRandomWord(string $description): string
    {
        $words = array_filter(
            preg_split('/[\s,.;:()]+/', $description),
            fn ($w) => strlen($w) > 3
        );
        return $words ? $words[array_rand($words)] : '...';
    }
}
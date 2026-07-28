<?php

namespace App\Services;

use App\Models\OracleAttribute;
use App\Models\OracleNumber;
use App\Models\OraclePosture;
use App\Models\OracleRace;
use App\Models\Reading;
use App\Models\YgoCard;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TarotService
{
    private const POSITIONS = ['Pasado', 'Presente', 'Futuro'];

    public function drawSpread(int $count = 3, ?string $question = null, ?int $userId = null): array
    {
        $seed = $this->ritualSeed($question);
        $cards = $this->drawCardsFromSeed($count, $seed);

        $spread = $cards->values()->map(function ($card, $i) {
            $posture = $this->posture($card);
            $postureData = OraclePosture::where('posture', $posture)->first();

            return [
                'position' => self::POSITIONS[$i] ?? null,
                'name' => $card->name,
                'race' => $card->race,
                'attribute' => $card->attribute,
                'level' => $card->level,
                'atk' => $card->atk,
                'def' => $card->def,
                'description' => $card->description_es ?? $card->description,
                'posture_label' => $postureData->label ?? null,
                'posture_icon' => $postureData->icon ?? null,
                'reading' => $this->renderCard($card),
                'image_url' => $card->image_url,
            ];
        });

        $coincidences = $this->findCoincidences($cards);
        $numerology = $this->numerologyReading($cards);
        $mysticMessage = $this->mysticMessage($cards);

        $reading = Reading::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $userId,
            'question' => $question,
            'cards' => $spread,
            'coincidences' => $coincidences,
            'numerology' => $numerology,
            'mystic_message' => $mysticMessage,
        ]);

        return [
            'uuid' => $reading->uuid,
            'cards' => $spread,
            'coincidences' => $coincidences,
            'numerology' => $numerology,
            'mystic_message' => $mysticMessage,
        ];
    }

    public function drawYesNo(?string $question = null): array
    {
        $seed = $this->ritualSeed($question);
        $card = $this->drawCardsFromSeed(1, $seed)->first();
        $posture = $this->posture($card);

        $answer = match ($posture) {
            'ofensiva' => 'Sí',
            'defensiva' => 'No',
            'equilibrada' => 'Depende de vos',
        };

        $postureData = OraclePosture::where('posture', $posture)->first();

        return [
            'answer' => $answer,
            'card' => [
                'name' => $card->name,
                'race' => $card->race,
                'attribute' => $card->attribute,
                'atk' => $card->atk,
                'def' => $card->def,
                'posture_label' => $postureData->label ?? null,
                'posture_icon' => $postureData->icon ?? null,
                'image_url' => $card->image_url,
            ],
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

        return implode('<br>', $parts);
    }

    private function raceEssence(?string $race): ?string
    {
        return $race.': '.OracleRace::where('race', $race)->value('essence');
    }

    private function attributeEssence(?string $attribute): ?string
    {
        return $attribute.': '.OracleAttribute::where('attribute', $attribute)->value('essence');
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

    private function extractRandomWord(string $ygoCardDescription): string
    {
        $words = array_filter(
            preg_split('/[\s,.;:()]+/', $ygoCardDescription),
            fn ($w) => strlen($w) > 3
        );

        return $words ? $words[array_rand($words)] : '...';
    }

    private function mysticMessage(Collection $cards): string
    {
        return $cards
            ->map(fn ($card) => $this->extractRandomWord($card->description_es ?? $card->description))
            ->shuffle()
            ->implode(' · ');
    }

    private function posture(YgoCard $card): string
    {
        $atk = $card->atk ?? 0;
        $def = $card->def ?? 0;

        if ($atk === 0 && $def === 0) {
            return 'equilibrada';
        }
        if ($def === 0) {
            return 'ofensiva';
        }
        if ($atk === 0) {
            return 'defensiva';
        }

        $ratio = $atk / $def;

        return match (true) {
            $ratio >= 1.5 => 'ofensiva',
            $ratio <= 0.67 => 'defensiva',
            default => 'equilibrada',
        };
    }
}

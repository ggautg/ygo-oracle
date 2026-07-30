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
            $raceEssence = $this->raceEssence($card->race);
            $attributeEssence = $this->attributeEssence($card->attribute);

            return [
                'position' => self::POSITIONS[$i] ?? null,
                'name' => $card->name,
                'race' => $card->race,
                'attribute' => $card->attribute,
                'level' => $card->level,
                'atk' => $card->atk,
                'def' => $card->def,
                'posture_label' => $postureData->label ?? null,
                'posture_icon' => $postureData->icon ?? null,
                'reading' => implode(' ', array_filter([$raceEssence, $attributeEssence])),
                'description' => $card->description_es ?? $card->description,
                'breakdown' => array_filter([
                    $card->race ? ['label' => 'Raza', 'value' => $card->race, 'essence' => $raceEssence] : null,
                    $card->attribute ? ['label' => 'Atributo', 'value' => $card->attribute, 'essence' => $attributeEssence] : null,
                    ['label' => 'Postura', 'value' => $postureData->label ?? null, 'essence' => "ATK {$card->atk} / DEF {$card->def}"],
                ]),
                'image_url' => $card->image_url,
            ];
        });

        $coincidences = $this->findCoincidences($cards);
        $numerology = $this->numerologyReading($cards);
        $mysticMessage = $this->mysticMessage($cards);
        $sigil = $this->generateSigil($seed);

        $reading = Reading::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $userId,
            'question' => $question,
            'cards' => $spread,
            'coincidences' => $coincidences,
            'numerology' => $numerology,
            'mystic_message' => $mysticMessage,
            'sigil' => $sigil,
        ]);

        return [
            'uuid' => $reading->uuid,
            'cards' => $spread,
            'coincidences' => $coincidences,
            'numerology' => $numerology,
            'mystic_message' => $mysticMessage,
            'sigil' => $sigil,
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

    private function generateSigil(string $seed): string
{
    $pointCount = 7;
    $cx = 100; $cy = 100; $radius = 75;

    // Puntos fijos, equiespaciados en el borde del círculo
    $points = [];
    for ($i = 0; $i < $pointCount; $i++) {
        $angle = ($i / $pointCount) * 2 * M_PI - M_PI / 2;
        $points[] = [
            round($cx + $radius * cos($angle), 1),
            round($cy + $radius * sin($angle), 1),
        ];
    }

    // El hash decide el ORDEN de conexión, no la posición — esto genera el cruce de líneas
    $order = [];
    $used = [];
    for ($i = 0; $i < $pointCount; $i++) {
        $hexPair = substr($seed, ($i * 2) % 60, 2);
        $idx = hexdec($hexPair) % $pointCount;
        while (in_array($idx, $used)) {
            $idx = ($idx + 1) % $pointCount;
        }
        $used[] = $idx;
        $order[] = $idx;
    }

    $path = "M {$points[$order[0]][0]},{$points[$order[0]][1]} ";
    for ($i = 1; $i < count($order); $i++) {
        $p = $points[$order[$i]];
        $path .= "L {$p[0]},{$p[1]} ";
    }

    $start = $points[$order[0]];
    $end = $points[$order[count($order) - 1]];

    return <<<SVG
    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
        <circle cx="{$cx}" cy="{$cy}" r="90" fill="none" stroke="#c9a227" stroke-width="1" opacity="0.5"/>
        <path d="{$path}" fill="none" stroke="#c9a227" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>
        <circle cx="{$start[0]}" cy="{$start[1]}" r="4" fill="none" stroke="#c9a227" stroke-width="1.2"/>
        <circle cx="{$end[0]}" cy="{$end[1]}" r="4" fill="none" stroke="#c9a227" stroke-width="1.2"/>
    </svg>
    SVG;
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

        return [
            'total' => $total,
            'digit' => $digit,
            'meaning' => $meaning,
            'steps' => $cards->pluck('level')->all(),
        ];
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
            ->map(fn ($card) => $this->extractRandomWord($card->description_es ?? $card->description))
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

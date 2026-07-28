<?php

namespace App\Console\Commands;

use App\Models\YgoCard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ImportYgoCards extends Command
{
    protected $signature = 'ygo:import';
    protected $description = 'Importa Monstruos Normales desde la API de YGOPRODeck';

    public function handle()
    {
        $this->info('Consultando la API de YGOPRODeck...');

        $response = Http::get('https://db.ygoprodeck.com/api/v7/cardinfo.php');

        if (!$response->successful()) {
            $this->error('La API no respondió bien. Código: ' . $response->status());
            return 1;
        }

        $cards = collect($response->json('data'));
        $normalMonsters = $cards->filter(fn ($card) => $card['frameType'] === 'normal');

        $this->info("Cartas totales en la API: {$cards->count()}");
        $this->info("Monstruos Normales encontrados: {$normalMonsters->count()}");

        $translator = (new GoogleTranslate)->setSource('en')->setTarget('es');
        $bar = $this->output->createProgressBar($normalMonsters->count());

        foreach ($normalMonsters as $card) {
            $descriptionEs = $this->translateSafely($translator, $card['desc']);
            $localImageUrl = $this->downloadImage($card);
            
            YgoCard::updateOrCreate(
                ['ygo_id' => $card['id']],
                [
                    'name' => $card['name'],
                    'type' => $card['type'],
                    'frame_type' => $card['frameType'],
                    'race' => $card['race'] ?? null,
                    'attribute' => $card['attribute'] ?? null,
                    'level' => $card['level'] ?? null,
                    'atk' => $card['atk'] ?? null,
                    'def' => $card['def'] ?? null,
                    'description' => $card['desc'],
                    'description_es' => $descriptionEs,
                    'banlist_status' => $card['banlist_info']['ban_tcg'] ?? null,
                    'image_url' => $localImageUrl,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Importación completa.');
    }

    private function translateSafely(GoogleTranslate $translator, string $text): string
    {
        try {
            return $translator->translate(str_replace('"', '', $text));
        } catch (\Exception $e) {
            $this->warn("No se pudo traducir una descripción, se guarda en inglés. Motivo: {$e->getMessage()}");
            return $text; // fallback: mejor en inglés que romper el import entero
        }
    }

    private function downloadImage(array $card): ?string
    {
        $imageUrl = $card['card_images'][0]['image_url'] ?? null;
        if (!$imageUrl) {
            return null;
        }

        $filename = "cards/{$card['id']}.jpg";

        // Si ya la bajamos en una corrida anterior, no la pedimos de nuevo
        if (Storage::disk('public')->exists($filename)) {
            return Storage::url($filename);
        }

        try {
            $imageResponse = Http::get($imageUrl);
            if (!$imageResponse->successful()) {
                return null;
            }
            Storage::disk('public')->put($filename, $imageResponse->body());
            return Storage::url($filename);
        } catch (\Exception $e) {
            $this->warn("No se pudo bajar la imagen de {$card['name']}: {$e->getMessage()}");
            return null;
        }
    }
}
<?php

namespace App\Console\Commands;

use App\Models\YgoCard;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('ygo:import')]
#[Description('Importa de momento Monstruos Normales desde la API de YGOPRODeck')]
class ImportYgoCards extends Command
{
    public function handle()
    {
        $this->info('Consultando la API de YGOPRODeck...');

        $response = Http::get('https://db.ygoprodeck.com/api/v7/cardinfo.php');

        if (! $response->successful()) {
            $this->error('La API no respondió bien. Código: '.$response->status());

            return 1;
        }

        $cards = collect($response->json('data'));

        // Filtro de v1: solo Monstruo Normal
        $normalMonsters = $cards->filter(fn ($card) => $card['frameType'] === 'normal');

        $this->info("Cartas totales en la API: {$cards->count()}");
        $this->info("Monstruos Normales encontrados: {$normalMonsters->count()}");

        $bar = $this->output->createProgressBar($normalMonsters->count());

        foreach ($normalMonsters as $card) {
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
                    'banlist_status' => $card['banlist_info']['ban_tcg'] ?? null,
                    'image_url' => $card['card_images'][0]['image_url'] ?? null,
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Importación completa.');
    }
}

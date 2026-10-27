<?php

namespace App\Console\Commands;

use App\Models\OracleTheme;
use App\Models\YgoCard;
use Illuminate\Console\Command;

class AssignCardThemes extends Command
{
    protected $signature = 'app:assign-card-themes {--force : Reassign all}';

    protected $description = 'Assign Yu-Gi-Oh! character/theme based on archetypes and keywords';

    public function handle(): int
    {
        $themes = OracleTheme::all();

        $query = YgoCard::query();
        if (! $this->option('force')) {
            $query->whereNull('theme_id');
        }

        $count = 0;
        $query->chunk(500, function ($cards) use ($themes, &$count) {
            foreach ($cards as $card) {
                $assigned = null;

                foreach ($themes as $theme) {
                    $keywords = $theme->keywords ?? [];
                    $archetypes = $theme->archetypes ?? [];

                    if ($card->archetype) {
                        foreach ($archetypes as $arch) {
                            if (stripos($card->archetype, $arch) !== false) {
                                $assigned = $theme;
                                break 2;
                            }
                        }
                    }

                    $text = strtolower(($card->name ?? '').' '.($card->description ?? '').' '.($card->description_es ?? ''));
                    foreach ($keywords as $kw) {
                        if (stripos($text, strtolower($kw)) !== false) {
                            $assigned = $theme;
                            break 2;
                        }
                    }
                }

                if ($assigned) {
                    $card->theme_id = $assigned->id;
                    $card->save();
                    $count++;
                }
            }
        });

        $this->info("Assigned themes to {$count} cards.");
        return self::SUCCESS;
    }
}

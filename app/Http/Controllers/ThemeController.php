<?php

namespace App\Http\Controllers;

use App\Models\OracleTheme;
use App\Models\YgoCard;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = OracleTheme::withCount('cards')
            ->orderBy('generation')
            ->get();

        return Inertia::render('Themes/Index', [
            'themes' => $themes,
        ]);
    }

    public function show(OracleTheme $theme)
    {
        $cards = YgoCard::where('theme_id', $theme->id)
            ->orderByRaw('level DESC')
            ->limit(48)
            ->get();

        return Inertia::render('Themes/Show', [
            'theme' => $theme,
            'cards' => $cards,
        ]);
    }

    public function oracle(Request $request)
    {
        $validated = $request->validate([
            'theme_id' => 'nullable|exists:oracle_themes,id',
        ]);

        $themes = OracleTheme::withCount('cards')->get();

        if (isset($validated['theme_id'])) {
            $theme = OracleTheme::findOrFail($validated['theme_id']);
            $cards = YgoCard::where('theme_id', $theme->id)->inRandomOrder()->limit(3)->get();
        } else {
            $theme = $themes->random();
            $cards = YgoCard::where('theme_id', $theme->id)->inRandomOrder()->limit(3)->get();
            if ($cards->count() < 3) {
                $cards = YgoCard::inRandomOrder()->limit(3)->get();
            }
        }

        return Inertia::render('Themes/Oracle', [
            'theme' => $theme ?? null,
            'cards' => $cards,
            'themes' => $themes,
        ]);
    }
}

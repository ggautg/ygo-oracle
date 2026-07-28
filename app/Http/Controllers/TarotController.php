<?php

namespace App\Http\Controllers;

use App\Services\TarotService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TarotController extends Controller
{
    public function index()
    {
        return Inertia::render('Tarot/Index');
    }

    public function draw(Request $request, TarotService $tarotService)
    {
        $validated = $request->validate([
            'question' => 'nullable|string|max:255',
        ]);

        $spread = $tarotService->drawSpread(3, $validated['question'] ?? null);

        return response()->json($spread);
    }
}
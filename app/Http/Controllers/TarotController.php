<?php

namespace App\Http\Controllers;

use App\Models\Reading;
use App\Services\TarotService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TarotController extends Controller
{
    public function index()
    {
        return Inertia::render('Tarot/Index');
    }

    public function draw(Request $request, TarotService $tarotService)
    {
        $validated = $request->validate(['question' => 'nullable|string|max:255']);
        $spread = $tarotService->drawSpread(3, $validated['question'] ?? null, $request->user()?->id);

        return response()->json($spread);
    }
public function yesNoIndex()
{
    return Inertia::render('Tarot/YesNo');
}

public function drawYesNo(Request $request, TarotService $tarotService)
{
    $validated = $request->validate(['question' => 'nullable|string|max:255']);
    return response()->json($tarotService->drawYesNo($validated['question'] ?? null));
}
    public function show(string $uuid)
    {
        $reading = Reading::where('uuid', $uuid)->firstOrFail();

        return Inertia::render('Tarot/Shared', [
            'reading' => $reading,
        ]);
    }
}

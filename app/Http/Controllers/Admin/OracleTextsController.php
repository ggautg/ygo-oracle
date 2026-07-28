<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{OracleRace, OracleAttribute, OracleNumber, OraclePosture};
use Illuminate\Http\Request;
use Inertia\Inertia;

class OracleTextsController extends Controller
{
    private const MODELS = [
        'races' => OracleRace::class,
        'attributes' => OracleAttribute::class,
        'numbers' => OracleNumber::class,
        'postures' => OraclePosture::class,
    ];

    private const FIELDS = [
        'races' => 'essence',
        'attributes' => 'essence',
        'numbers' => 'meaning',
        'postures' => 'label',
    ];

    public function index()
    {
        return Inertia::render('Admin/OracleTexts', [
            'races' => OracleRace::orderBy('race')->get(),
            'attributes' => OracleAttribute::orderBy('attribute')->get(),
            'numbers' => OracleNumber::orderBy('number')->get(),
            'postures' => OraclePosture::orderBy('posture')->get(),
        ]);
    }

    public function update(Request $request, string $type, int $id)
    {
        abort_unless(array_key_exists($type, self::MODELS), 404);

        $field = self::FIELDS[$type];
        $validated = $request->validate([$field => 'required|string|max:1000']);

        self::MODELS[$type]::findOrFail($id)->update($validated);

        return back();
    }
}
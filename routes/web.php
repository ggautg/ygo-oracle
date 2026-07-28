<?php

use App\Http\Controllers\Admin\OracleTextsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarotController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/oraculo-textos', [OracleTextsController::class, 'index'])->name('admin.oracle.index');
    Route::put('/admin/oraculo-textos/{type}/{id}', [OracleTextsController::class, 'update'])->name('admin.oracle.update');
});

Route::get('/oraculo', [TarotController::class, 'index'])->name('tarot.index');
Route::post('/oraculo/tirar', [TarotController::class, 'draw'])->name('tarot.draw');
Route::get('/oraculo/t/{uuid}', [TarotController::class, 'show'])->name('tarot.show');

require __DIR__.'/auth.php';

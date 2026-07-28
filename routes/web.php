<?php

use App\Http\Controllers\Admin\OracleTextsController;
use App\Http\Controllers\TarotController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TarotController::class, 'index'])->name('tarot.home');

Route::middleware('auth')->group(function () {
    Route::get('/admin/oraculo-textos', [OracleTextsController::class, 'index'])->name('admin.oracle.index');
    Route::put('/admin/oraculo-textos/{type}/{id}', [OracleTextsController::class, 'update'])->name('admin.oracle.update');
});

Route::get('/oraculo', [TarotController::class, 'index'])->name('tarot.index');
Route::post('/oraculo/tirar', [TarotController::class, 'draw'])->name('tarot.draw');
Route::get('/oraculo/t/{uuid}', [TarotController::class, 'show'])->name('tarot.show');
Route::get('/oraculo/si-no', [TarotController::class, 'yesNoIndex'])->name('tarot.yesno.index');
Route::post('/oraculo/si-no/tirar', [TarotController::class, 'drawYesNo'])->name('tarot.yesno.draw');

require __DIR__.'/auth.php';

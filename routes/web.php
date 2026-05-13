<?php

use App\Http\Controllers\CampeonatoPublicoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Vistas públicas (sin login) ─────────────────────────────────────────────
Route::get('/', [CampeonatoPublicoController::class, 'index'])->name('inicio');

Route::prefix('campeonatos')->name('campeonatos.')->group(function () {
    Route::get('/{slug}',              [CampeonatoPublicoController::class, 'show'])->name('show');
    Route::get('/{slug}/equipos/{id}', [CampeonatoPublicoController::class, 'equipo'])->name('equipo');
});

// ─── Rutas de perfil (auth) ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {       // ← agrega esto
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
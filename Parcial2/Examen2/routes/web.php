<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Route;

// Rutas Públicas / Consulta
Route::get('/', [TorneoController::class, 'index'])->name('torneos.index');
Route::get('/torneos/{id}', [TorneoController::class, 'show'])->name('torneos.show');

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas para Jugadores Autenticados
Route::middleware(['auth', 'role:jugador'])->group(function () {
    Route::post('/torneos/{torneo}/inscribirse', [InscripcionController::class, 'store'])->name('torneos.inscribirse');
    Route::get('/mis-torneos', [InscripcionController::class, 'misTorneos'])->name('jugador.mis-torneos');
    Route::delete('/inscripciones/{inscripcion}/cancelar', [InscripcionController::class, 'cancelar'])->name('inscripciones.cancelar');
});

// Rutas de Administración
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/torneos', [TorneoController::class, 'adminIndex'])->name('torneos.index');
    Route::get('/torneos/crear', [TorneoController::class, 'create'])->name('torneos.create');
    Route::post('/torneos', [TorneoController::class, 'store'])->name('torneos.store');
    Route::get('/torneos/{torneo}/editar', [TorneoController::class, 'edit'])->name('torneos.edit');
    Route::put('/torneos/{torneo}', [TorneoController::class, 'update'])->name('torneos.update');
    Route::delete('/torneos/{torneo}', [TorneoController::class, 'destroy'])->name('torneos.destroy');

    // Gestión de participantes
    Route::get('/torneos/{torneo}/participantes', [InscripcionController::class, 'verParticipantes'])->name('torneos.participantes');
    Route::delete('/inscripciones/{inscripcion}/baja', [InscripcionController::class, 'bajaAdmin'])->name('inscripciones.baja');
});

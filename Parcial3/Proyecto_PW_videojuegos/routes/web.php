<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeveloperGameController;
use App\Http\Controllers\LauncherController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ClientTicketController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

// 1. Redirección raíz
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isDeveloper()) return redirect()->route('developer.games.index');
        if ($user->isSupport()) return redirect()->route('support.tickets');
        return redirect()->route('launcher.dashboard');
    }

    return redirect()->route('login');
});

// 2. Rutas para invitados (Login y Registro)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Rutas protegidas (Usuarios autenticados)
Route::middleware('auth')->group(function () {
    // Cierre de sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Panel Principal del Launcher (Tienda y Biblioteca del Jugador)
    Route::get('/launcher', [LauncherController::class, 'index'])->name('launcher.dashboard');
    Route::post('/launcher/games/{game}/acquire', [LauncherController::class, 'addToLibrary'])->name('launcher.acquire');

    // Portal de Desarrollador / Empresa (Subida y Gestión de Videojuegos)
    Route::middleware('role:developer,admin')->prefix('developer')->name('developer.')->group(function () {
        Route::resource('games', DeveloperGameController::class);
    });

    // Panel de Administrador (Moderación de juegos, métricas y usuarios)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/games/{game}/approve', [AdminController::class, 'approveGame'])->name('games.approve');
        Route::post('/games/{game}/reject', [AdminController::class, 'rejectGame'])->name('games.reject');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::post('/users/{user}/role', [AdminController::class, 'changeUserRole'])->name('users.role');
    });

    // Consola de Soporte Técnico (Gestión de Tickets e Incidencias)
    Route::middleware('role:support,admin')->prefix('support')->name('support.')->group(function () {
        Route::get('/tickets', [SupportController::class, 'tickets'])->name('tickets');
        Route::post('/games/{game}/approve', [SupportController::class, 'approveGame'])->name('games.approve');
        Route::post('/games/{game}/reject', [SupportController::class, 'rejectGame'])->name('games.reject');
        Route::post('/tickets/{id}/resolve', [SupportController::class, 'resolve'])->name('tickets.resolve');
        // Consultar el detalle de un ticket
        Route::get('/tickets/{ticket}', [SupportController::class, 'show'])
            ->name('tickets.show');

        // Enviar una respuesta
        Route::post('/tickets/{ticket}/reply', [SupportController::class, 'reply'])
            ->name('tickets.reply');
    });

    Route::middleware('role:client')->prefix('client')->name('client.')->group(function () {
        Route::get('/tickets', [ClientTicketController::class, 'index'])
            ->name('tickets.index');

        Route::get('/tickets/create', [ClientTicketController::class, 'create'])
            ->name('tickets.create');

        Route::post('/tickets', [ClientTicketController::class, 'store'])
            ->name('tickets.store');
        // Consultar el detalle de un ticket
        Route::get('/tickets/{ticket}', [ClientTicketController::class, 'show'])
            ->name('tickets.show');

        // Enviar una respuesta
        Route::post('/tickets/{ticket}/reply', [ClientTicketController::class, 'reply'])
            ->name('tickets.reply');

        // Monedero NL Coins
        Route::get('/wallet', [WalletController::class, 'index'])
        ->name('wallet');

        Route::post('/wallet/redeem', [WalletController::class, 'redeem'])
        ->name('wallet.redeem');
    });
});
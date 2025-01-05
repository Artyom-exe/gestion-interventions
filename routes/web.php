<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\AdminController;
use Inertia\Inertia;

// Page d'accueil
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => app()->version(), // Utilisation de `app()->version()` pour la version Laravel
        'phpVersion' => PHP_VERSION, // Constante PHP native pour la version PHP
    ]);
});

// Middleware pour les utilisateurs authentifiés
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    // Redirection basée sur le rôle
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'technicien' => redirect()->route('tickets.index'),
            default => redirect('/')->with('error', 'Accès non autorisé pour ce rôle.'),
        };
    })->name('dashboard');

    // Routes pour les tickets
    Route::resource('tickets', TicketsController::class)->except(['edit']); // Techniciens
    Route::delete('/tickets/{ticket}/images/{image}', [TicketsController::class, 'deleteImage'])->name('tickets.images.destroy');

    // Tableau de bord pour les administrateurs
    Route::middleware('auth')->group(function () {
        Route::get('/tickets/create', [TicketsController::class, 'create'])->name('tickets.create');
        Route::get('/tickets/{ticket}/edit', [TicketsController::class, 'edit'])->name('tickets.edit');
        Route::delete('/tickets/{ticket}', [TicketsController::class, 'destroy'])->name('tickets.destroy');
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    });
});

// Middleware supplémentaire pour administrateurs uniquement
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
});

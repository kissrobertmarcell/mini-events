<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSignupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [EventController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('events', EventController::class)->except(['index', 'show']);

    Route::post('events/{event}/register', [EventSignupController::class, 'store'])
        ->name('events.register');
    Route::delete('events/{event}/register', [EventSignupController::class, 'destroy'])
        ->name('events.unregister');
});

require __DIR__.'/auth.php';

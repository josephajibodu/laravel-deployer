<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Server Providers
    Route::resource('server-providers', App\Http\Controllers\ServerProviderController::class)
        ->parameters(['server-providers' => 'serverProvider']);
    Route::post('server-providers/{serverProvider}/test', [App\Http\Controllers\ServerProviderController::class, 'test'])
        ->name('server-providers.test');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

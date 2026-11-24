<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/tac.php';

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/favourites', \App\Http\Controllers\FavouritesController::class)
        ->name('favourites.index');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

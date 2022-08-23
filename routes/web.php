<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/tac.php';


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

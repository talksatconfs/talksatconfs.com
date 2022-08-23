<?php

use Illuminate\Support\Facades\Route;

// home page
Route::get('/', \App\Http\Controllers\HomepageController::class)
    ->name('homepage');
Route::get('/test', \App\Http\Controllers\TestpageController::class)
    ->name('testpage');
Route::get('/about-us', \App\Http\Controllers\AboutUsController::class)
    ->name('about-us');

Route::get('/robots.txt', \App\Http\Controllers\RobotsController::class)
    ->name('robots-txt');

// search
Route::resource('/search', \App\Http\Controllers\GlobalSearchController::class)
    ->only(['index']);

// redirects
Route::get('/conferences/{uuid}/{slug}', \App\Http\Controllers\ConferenceRedirectController::class);
Route::get('/events/{uuid}/{slug}', \App\Http\Controllers\EventRedirectController::class);
Route::get('/talks/{slug}', [\App\Http\Controllers\TalkRedirectController::class, 'new_index']);
Route::get('/talks/{uuid}/{slug}', [\App\Http\Controllers\TalkRedirectController::class, 'index']);

Route::get('/speakers/{uuid}/{slug}', \App\Http\Controllers\SpeakerRedirectController::class);

// entities
// :conference
Route::get('/conferences', [\App\Http\Controllers\ConferenceController::class, 'index'])
    ->name('conferences.index');
Route::get('/conferences/{conference:slug}', [\App\Http\Controllers\ConferenceController::class, 'show'])
    ->name('conferences.show');

// :event
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])
    ->name('events.index');
Route::get('/events/{event:slug}', [\App\Http\Controllers\EventController::class, 'show'])
    ->name('events.show');

// :talk
Route::get('/talks', [\App\Http\Controllers\TalkController::class, 'index'])
    ->name('talks.index');
Route::get('/events/{event}/talks/{talk}', [\App\Http\Controllers\TalkController::class, 'show'])
    ->name('talks.show');

// :speaker
Route::get('/speakers', [\App\Http\Controllers\SpeakerController::class, 'index'])
    ->name('speakers.index');
Route::get('/speakers/{speaker:slug}', [\App\Http\Controllers\SpeakerController::class, 'show'])
    ->name('speakers.show');

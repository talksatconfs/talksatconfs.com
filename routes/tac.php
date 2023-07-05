<?php

use Domain\TalksAtConfs\Http\Controllers;
use Illuminate\Support\Facades\Route;

// home page
Route::get('/', Controllers\HomepageController::class)
    ->name('homepage');
Route::get('/test', Controllers\TestpageController::class)
    ->name('testpage');
Route::get('/about-us', Controllers\AboutUsController::class)
    ->name('about-us');

Route::get('/robots.txt', Controllers\RobotsController::class)
    ->name('robots-txt');

// search
Route::resource('/search', Controllers\GlobalSearchController::class)
    ->only(['index']);

// redirects
Route::get('/conferences/{uuid}/{slug}', Controllers\ConferenceRedirectController::class);
Route::get('/events/{uuid}/{slug}', Controllers\EventRedirectController::class);
Route::get('/talks/{slug}', [Controllers\TalkRedirectController::class, 'new_index']);
Route::get('/talks/{uuid}/{slug}', [Controllers\TalkRedirectController::class, 'index']);

Route::get('/speakers/{uuid}/{slug}', Controllers\SpeakerRedirectController::class);

// entities
// :conference
Route::get('/conferences', [Controllers\ConferenceController::class, 'index'])
    ->name('conferences.index');
Route::get('/conferences/{conference:slug}', [Controllers\ConferenceController::class, 'show'])
    ->name('conferences.show');

// :event
Route::get('/events', [Controllers\EventController::class, 'index'])
    ->name('events.index');
Route::get('/events/{event:slug}', [Controllers\EventController::class, 'show'])
    ->name('events.show');

// :talk
Route::get('/talks', [Controllers\TalkController::class, 'index'])
    ->name('talks.index');
Route::get('/events/{event}/talks/{talk}', [Controllers\TalkController::class, 'show'])
    ->name('talks.show');

// :speaker
Route::get('/speakers', [Controllers\SpeakerController::class, 'index'])
    ->name('speakers.index');
Route::get('/speakers/{speaker:slug}', [Controllers\SpeakerController::class, 'show'])
    ->name('speakers.show');

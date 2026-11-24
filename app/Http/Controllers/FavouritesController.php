<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FavouritesController extends Controller
{
    public function __invoke(Request $request): View
    {
        $ids = $request->user()
            ->favourites()
            ->latest('id')
            ->get(['favouritable_type', 'favouritable_id'])
            ->groupBy('favouritable_type')
            ->map(fn ($favourites) => $favourites->pluck('favouritable_id')->all());

        return view('favourites.index', [
            'title' => 'My favourites',
            'description' => '',
            'canonicalurl' => route('favourites.index'),
            'talks' => Talk::with('event')->whereIn('id', $ids->get('talk', []))->get(),
            'events' => Event::with('conference')->whereIn('id', $ids->get('event', []))->get(),
            'conferences' => Conference::whereIn('id', $ids->get('conference', []))->get(),
        ]);
    }
}

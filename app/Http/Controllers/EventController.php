<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EventSearch;
use App\Http\Requests\EventTalkSearch;
use Domain\TalksAtConfs\Models\Event;

class EventController extends Controller
{
    public function index(EventSearch $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('events.index', [
            'title' => 'List of Events',
            'canonicalurl' => route('events.index'),
            'description' => 'List of all the compiled events around the world',
        ]);
    }

    public function show(Event $event, EventTalkSearch $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('events.show', [
            'title' => $event->name . ' - Event',
            'canonicalurl' => $event->canonical_url,
            'description' => 'List of all the talks under the event - ' . $event->name,
            'event' => $event,
        ]);
    }
}

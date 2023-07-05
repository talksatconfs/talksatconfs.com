<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TalkSearch;
use Domain\TalksAtConfs\Models\Talk;

class TalkController extends Controller
{
    public function index(TalkSearch $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('talks.index', [
            'title' => 'List of Talks',
            'canonicalurl' => route('talks.index'),
            'description' => 'List of all the compiled talks from the events / conferences around the world.',
        ]);
    }

    public function show($event, $slug): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $talk = Talk::query()
            ->select(['id', 'title', 'slug', 'description', 'link', 'talk_date', 'video_start_time', 'event_id'])
            ->with(['event', 'speakers'])
            ->whereRelation('event', 'slug', $event)
            ->whereSlug($slug)
            ->firstOrFail();

        return view('talks.show', [
            'title' => 'Talk - '.$talk->title.' by '.$talk->speakers_names.' at '.$talk->event->name,
            'canonicalurl' => $talk->canonical_url,
            'description' => $talk->title.' by '.$talk->speakers_names.' at '.$talk->event->name,
            'talk' => $talk,
        ]);
    }
}

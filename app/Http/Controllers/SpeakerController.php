<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SpeakerSearch;
use Domain\TalksAtConfs\Models\Speaker;

class SpeakerController extends Controller
{
    public function index(SpeakerSearch $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('speakers.index', [
            'title' => 'List of Speakers',
            'canonicalurl' => route('speakers.index'),
            'description' => 'List of all the speakers who have given a talk / presentation in several events / conferences around the world.',
        ]);
    }

    public function show(Speaker $speaker, SpeakerSearch $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('speakers.show', [
            'title' => $speaker->name,
            'canonicalurl' => $speaker->canonical_url,
            'description' => 'Details about ' . $speaker->name . ' & the talks given in various events / conferences',
            'speaker' => $speaker,
        ]);
    }
}

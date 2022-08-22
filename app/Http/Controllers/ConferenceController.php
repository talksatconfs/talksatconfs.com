<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ConferenceEventSearch;
use App\Http\Requests\ConferenceSearch;
use Domain\TalksAtConfs\Models\Conference;

class ConferenceController extends Controller
{
    public function index(ConferenceSearch $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('conferences.index', [
            'title' => 'List of Conferences',
            'description' => 'List of all the compiled conferences around the world',
            'canonicalurl' => route('conferences.index'),
        ]);
    }

    public function show(Conference $conference, ConferenceEventSearch $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('conferences.show', [
            'title' => $conference->name . ' - Conference',
            'canonicalurl' => $conference->canonical_url,
            'description' => $conference->description ?? 'List of all the events under the conference ' . $conference->name,
            'conference' => $conference,
        ]);
    }
}

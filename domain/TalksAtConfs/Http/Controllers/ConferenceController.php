<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConferenceEventSearch;
use App\Http\Requests\ConferenceSearch;
use Domain\TalksAtConfs\Models\Conference;
use Illuminate\Contracts\View\View;

class ConferenceController extends Controller
{
    public function index(ConferenceSearch $request): View
    {
        return view('conferences.index', [
            'title' => 'List of Conferences',
            'description' => 'List of all the compiled conferences around the world',
            'canonicalurl' => route('conferences.index'),
        ]);
    }

    public function show(Conference $conference, ConferenceEventSearch $request): View
    {
        return view('conferences.show', [
            'title' => $conference->name.' - Conference',
            'canonicalurl' => $conference->canonical_url,
            'description' => $conference->description ?? 'List of all the events under the conference '.$conference->name,
            'conference' => $conference,
        ]);
    }
}

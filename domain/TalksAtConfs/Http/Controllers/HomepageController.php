<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Http\Controllers;

use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function __invoke()
    {
        return view('welcome', [
            'title' => 'Talks at Confs - Home - Collection of all possible conference talks at one place',
            'description' => 'Collection of all possible conferences & the talks compiled on single platform.',
            'canonicalurl' => route('homepage'),
        ]);
    }
}

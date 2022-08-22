<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class TestpageController
{
    public function __invoke()
    {
        // dd('hi');
        return view('test', [
            'title' => 'Talks at Confs - Home - Collection of all possible conference talks at one place',
            'description' => 'Collection of all possible conferences & the talks compiled on single platform.',
            'canonicalurl' => route('homepage'),
        ]);
    }
}

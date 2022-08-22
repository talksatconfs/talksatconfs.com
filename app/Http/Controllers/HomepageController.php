<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class HomepageController
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

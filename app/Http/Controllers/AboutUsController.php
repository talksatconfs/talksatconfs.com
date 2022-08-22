<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class AboutUsController extends Controller
{
    public function __invoke()
    {
        return view('about-us', [
            'title' => 'Talks at Confs - About Us',
            'description' => '',
            'canonicalurl' => route('about-us'),
        ]);
    }
}

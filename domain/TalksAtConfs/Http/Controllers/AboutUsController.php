<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class AboutUsController extends Controller
{
    public function __invoke(): View
    {
        return view('about-us', [
            'title' => 'Talks at Confs - About Us',
            'description' => '',
            'canonicalurl' => route('about-us'),
        ]);
    }
}

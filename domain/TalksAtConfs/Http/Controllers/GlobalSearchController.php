<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        return view('search-results', [
            'title' => 'Search Results',
            'canonicalurl' => route('search.index'),
            'description' => 'Search result for the keyword - '.$request->get('query'),
        ]);
    }
}

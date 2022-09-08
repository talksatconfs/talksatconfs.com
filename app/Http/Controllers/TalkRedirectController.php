<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Http\Request;

class TalkRedirectController extends Controller
{
    public function index(Request $request, $uuid, $slug)
    {
        $talk = Talk::whereSlug($slug)->first() ?? Talk::search($slug)->first();
        if (! is_null($talk)) {
            return response()->redirectTo($talk->canonical_url, 301);
        }
        abort(404);
    }

    public function new_index(Request $request, $slug)
    {
        $talks = Talk::whereSlug($slug)->paginate(10) ?? Talk::search($slug)->paginate(10);

        if ($talks->count() === 1) {
            $talk = $talks->first();
            if (! is_null($talk)) {
                return response()->redirectTo($talk->canonical_url, 301);
            }
        }

        if ($talks->count() > 1) {
            return view('talks.index', [
                'title' => 'List of Talks with keyword - ' . $slug,
                'canonicalurl' => route('talks.index'),
                'description' => 'List of all the compiled talks from the events / conferences around the world.',
                'talks' => $talks,
            ]);
        }

        abort(404);
    }
}

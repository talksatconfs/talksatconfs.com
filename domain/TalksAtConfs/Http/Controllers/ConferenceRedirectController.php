<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Http\Controllers;

use App\Http\Controllers\Controller;
use Domain\TalksAtConfs\Models\Conference;
use Illuminate\Http\Request;

class ConferenceRedirectController extends Controller
{
    public function __invoke(Request $request, $uuid, $slug)
    {
        $conference = Conference::whereSlug($slug)->first() ?? Conference::search($slug)->first();
        if (! is_null($conference)) {
            return response()->redirectTo($conference->canonical_url, 301);
        }
        abort(404);
    }
}

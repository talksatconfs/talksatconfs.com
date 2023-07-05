<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Http\Controllers;

use App\Http\Controllers\Controller;
use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerRedirectController extends Controller
{
    public function __invoke(Request $request, $uuid, $slug)
    {
        $speaker = Speaker::whereSlug($slug)->first() ?? Speaker::search($slug)->first();
        if (! is_null($speaker)) {
            return response()->redirectTo($speaker->canonical_url, 301);
        }
        abort(404);
    }
}

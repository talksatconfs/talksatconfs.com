<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Domain\TalksAtConfs\Models\Event;
use Illuminate\Http\Request;

class EventRedirectController extends Controller
{
    public function __invoke(Request $request, $uuid, $slug)
    {
        $event = Event::whereSlug($slug)->first() ?? Event::search($slug)->first();
        if (! is_null($event)) {
            return response()->redirectTo($event->canonical_url, 301);
        }
        abort(404);
    }
}

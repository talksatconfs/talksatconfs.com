<?php

declare(strict_types=1);

namespace App\View\Components\Conference;

use Illuminate\View\Component;

class Details extends Component
{
    public function __construct(public $conference)
    {
        // $this->events = $events;
    }

    public function render()
    {
        return view('components.conference.details');
    }
}

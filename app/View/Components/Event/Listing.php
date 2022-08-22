<?php

declare(strict_types=1);

namespace App\View\Components\Event;

use Illuminate\View\Component;

class Listing extends Component
{
    public function __construct(public $events, public $type = 'title')
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.event.listing');
    }
}

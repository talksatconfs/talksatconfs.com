<?php

declare(strict_types=1);

namespace App\View\Components\Conference;

use Illuminate\View\Component;

class Listing extends Component
{
    public function __construct(public $conferences)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.conference.listing');
    }
}

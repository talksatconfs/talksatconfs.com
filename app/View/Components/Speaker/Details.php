<?php

declare(strict_types=1);

namespace App\View\Components\Speaker;

use Illuminate\View\Component;

class Details extends Component
{
    public function __construct(public $speaker)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.speaker.details');
    }
}

<?php

declare(strict_types=1);

namespace App\View\Components\Talk;

use Illuminate\View\Component;

class Video extends Component
{
    public function __construct(public $video, public $startTime)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.talk.video');
    }
}

<?php

declare(strict_types=1);

namespace App\View\Components\Homepage;

use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use Domain\TalksAtConfs\Models\Video;
use Illuminate\View\Component;

class Statistics extends Component
{
    public $conferenceCount;

    public $eventCount;

    public $speakerCount;

    public $talkCount;

    public $videoCount;

    public function __construct()
    {
        $this->conferenceCount = number_format(Conference::count());
        $this->eventCount = number_format(Event::count());
        $this->speakerCount = number_format(Speaker::count());
        $this->talkCount = number_format(Talk::count());
        $this->videoCount = number_format(Video::count());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.homepage.statistics');
    }
}

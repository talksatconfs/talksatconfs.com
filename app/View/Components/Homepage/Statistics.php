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
    public $conference_count;

    public $event_count;

    public $speaker_count;

    public $talk_count;

    public $video_count;

    public function __construct()
    {
        $this->conference_count = number_format(Conference::count());
        $this->event_count = number_format(Event::count());
        $this->speaker_count = number_format(Speaker::count());
        $this->talk_count = number_format(Talk::count());
        $this->video_count = number_format(Video::count());
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

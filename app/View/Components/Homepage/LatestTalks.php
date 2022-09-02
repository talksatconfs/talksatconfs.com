<?php

namespace App\View\Components\Homepage;

use Domain\TalksAtConfs\Models\Talk;
use Illuminate\View\Component;

class LatestTalks extends Component
{
    public $talks;

    public function __construct()
    {
        $this->talks = Talk::details()
            ->sortByTalkDate()
            ->limit(6)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.homepage.latest-talks');
    }
}

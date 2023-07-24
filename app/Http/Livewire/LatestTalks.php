<?php

namespace App\Http\Livewire;

use Domain\TalksAtConfs\Models\Talk;
use Livewire\Component;

class LatestTalks extends Component
{
    public $latestWithVideo = false;

    protected $queryString = ['latestWithVideo'];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('livewire.latest-talks', [
            'talks' => Talk::details($this->latestWithVideo)
                ->sortByTalkDate()
                ->limit(6)
                ->get(),
        ]);
    }
}

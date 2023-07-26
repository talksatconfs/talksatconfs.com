<?php

namespace App\Livewire;

use Domain\TalksAtConfs\Models\Talk;
use Livewire\Component;

class RecentlyAddedTalks extends Component
{
    public $recentWithVideo = false;

    protected $queryString = [
        'recentWithVideo' => ['keep' => true],
    ];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('livewire.recently-added-talks', [
            'talks' => Talk::details($this->recentWithVideo)
                ->sortByCreatedDate()
                ->limit(6)
                ->get(),
        ]);
    }
}

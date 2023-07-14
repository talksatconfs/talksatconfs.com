<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Domain\TalksAtConfs\Models\Talk;

class RecentlyAddedTalks extends Component
{
    public $talksWithVideos = false;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('livewire.recently-added-talks', [
            'talks' => Talk::details()
                ->sortByCreatedDate()
                ->limit(6)
                ->get()
        ]);
    }
}

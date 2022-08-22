<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class SpeakerListing extends Component
{
    public $query;

    protected $queryString = ['query'];

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        if ($this->query) {
            $speakers = Speaker::search($this->query)->query(function (Builder $builder) {
                $builder->details();
            });
        } else {
            $speakers = Speaker::details();
        }

        $speakers = $speakers->paginate(12);

        return view('livewire.speaker-listing', [
            'speakers' => $speakers,
        ]);
    }
}

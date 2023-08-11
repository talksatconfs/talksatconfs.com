<?php

declare(strict_types=1);

namespace App\Livewire;

use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class SpeakerListing extends Component
{
    use WithPagination;

    public $query;

    protected $queryString = ['query'];

    public function render(): View|Factory
    {
        if ($this->query) {
            $speakers = Speaker::search($this->query)
                ->query(function (Builder $builder) {
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
<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Domain\TalksAtConfs\Models\Conference;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ConferenceListing extends Component
{
    public $query;

    protected $queryString = ['query'];

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        if ($this->query) {
            $conferences = Conference::search($this->query)->query(function (Builder $builder) {
                $builder->details();
            });
        } else {
            $conferences = Conference::details();
        }

        $conferences = $conferences->paginate(10);

        return view('livewire.conference-listing', [
            'conferences' => $conferences,
        ]);
    }
}

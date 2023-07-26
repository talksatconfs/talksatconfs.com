<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Domain\TalksAtConfs\Models\Conference;

class ConferenceListing extends Component
{
    use WithPagination;

    public $query;

    protected $queryString = ['query'];

    public function render(): View|Factory
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

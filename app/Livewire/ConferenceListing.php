<?php

declare(strict_types=1);

namespace App\Livewire;

use Domain\TalksAtConfs\Models\Conference;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ConferenceListing extends Component
{
    use WithPagination;

    #[Url(history: true, as: 'q')]
    public $query;

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

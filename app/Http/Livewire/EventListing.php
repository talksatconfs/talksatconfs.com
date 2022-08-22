<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Domain\TalksAtConfs\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class EventListing extends Component
{
    public $query;

    public $conference;

    protected $queryString = ['query'];

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        if ($this->query) {
            $events = Event::search($this->query)
                ->query(function (Builder $builder) {
                    $builder->details();
                })
                ->when($this->conference, fn ($builder) => $builder->where('conference_id', $this->conference->id))
                ->orderBy('from_date', 'desc');
        } else {
            $events = Event::details()
                ->when($this->conference, fn ($builder) => $builder->where('conference_id', $this->conference->id));
        }

        $events = $events->paginate(10);

        return view('livewire.event-listing', [
            'events' => $events,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class TalkListing extends Component
{
    public $query;

    public $event;

    public $speaker;

    protected $queryString = ['query'];

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        if ($this->query) {
            $talks = Talk::search($this->query)->query(function (Builder $builder) {
                $builder->details()
                    ->sortByTalkDate();
            })
            ->when($this->event, fn ($builder) => $builder->where('event_id', $this->event->id))
            ->when($this->speaker, fn ($builder) => $builder->where('speaker_ids', $this->speaker->id))
            ->orderBy('talk_date', 'desc');
        } else {
            $talks = Talk::details()
                ->sortByTalkDate()
                ->when($this->event, fn ($builder) => $builder->where('event_id', $this->event->id))
                ->when($this->speaker, function ($builder) {
                    $builder->whereHas('speakers', function ($query) {
                        $query->where('speaker_id', $this->speaker->id);
                    });
                });
        }

        $talks = $talks->paginate(12);

        return view('livewire.talk-listing', [
            'talks' => $talks,
        ]);
    }
}

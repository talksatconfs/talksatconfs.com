<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Database\Eloquent\Builder;

class TalkListing extends Component
{
    use WithPagination;

    #[Url(history: true, as: 'q')]
    public $query;

    #[Url(history: true)]
    public $withVideo = false;

    public $event;

    public $speaker;

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        if ($this->query) {
            $talks = Talk::search($this->query)->query(function (Builder $builder) {
                $builder->details($this->withVideo)
                    ->sortByTalkDate();
            })
                ->when($this->event, fn ($builder) => $builder->where('event_id', $this->event->id))
                ->when($this->speaker, fn ($builder) => $builder->where('speaker_ids', $this->speaker->id))
                ->orderBy('talk_date', 'desc');
        } else {
            $talks = Talk::details($this->withVideo)
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

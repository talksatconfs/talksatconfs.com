<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Contracts\UuidForModel;
use Domain\TalksAtConfs\Database\Factories\TalkFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * Domain\TalksAtConfs\Models\Talk
 *
 * @property string $event
 * @property string $title
 * @property string $slug
 * @property mixed $speakers
 * @property string $uuid
 * @property int $id
 * @property mixed $talk_date
 * @property string $link
 */
class Talk extends AbstractTacModel
{
    use HasFactory;
    use Searchable;
    use UuidForModel;

    protected $guarded = [];

    protected $casts = [
        'talk_date' => 'datetime',
    ];

    protected static function newFactory(): Factory
    {
        return TalkFactory::new();
    }

    // Mutators
    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getCanonicalUrlAttribute(): string
    {
        return route('talks.show', [
                'event' => $this->event->slug,
                'talk' => $this->slug,
            ]);
    }

    public function conference(): HasOneThrough
    {
        return $this->hasOneThrough(Conference::class, Event::class, 'id', 'id', 'event_id', 'conference_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class)
            ->where('name', '<>', '');
    }

    public function getSpeakersNamesAttribute()
    {
        if (! is_null($this->speakers)) {
            return $this->speakers->pluck('name')->join(', ', ' & ');
        }
    }

    public function getDisplayTalkDateAttribute()
    {
        return $this->talk_date?->format('M d, Y');
    }

    public function getSpeakersLinksAttribute()
    {
        if (! is_null($this->speakers)) {
            return $this->speakers
                ->map(function ($speaker) {
                    return '<a href="' . $speaker->canonical_url . '" title="' . $speaker->name . '">
                            ' . $speaker->name . '
                        </a>';
                })
                ->join(', ', ' & ');
        }
    }

    public function videos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }

    public function slides(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Slide::class);
    }

    public function scopeDetails(Builder $query): void
    {
        $query->select([
            'id', 'title', 'slug',
            'description', 'link', 'talk_date',
            'video_start_time', 'event_id',
        ])
        ->with([
            'videos', 'speakers', 'event',
        ])
        ->withCount('videos');
    }

    public function scopeSortByTalkDate(Builder $query): void
    {
        $query->orderByDesc('talk_date');
    }

    public function scopeSortByCreatedDate(Builder $query): void
    {
        $query->orderByDesc('created_at');
    }

    public function searchableAs(): string
    {
        return 'talks_index';
    }

    /**
     * @psalm-return array{id: integer, uuid: string, title: string, link: string, talk_date: mixed, speakers: mixed, speaker_ids: mixed, event_id: mixed, event_name: mixed, event_location: mixed, conference: mixed, conference_id: mixed}
     */
    public function toSearchableArray(): array
    {
        $event = $this->event()->first();
        $conference = $this->conference()->first();
        $speakers = $this->speakers()->get();

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'link' => $this->link,
            'talk_date' => $this->talk_date,
            'speakers' => $speakers->pluck('name')->join(' and '),
            'speaker_ids' => $speakers->pluck('id')->toArray(),
            'event_id' => $event->id,
            'event_name' => $event->name,
            'event_location' => $event->location,
            'conference' => $conference->name,
            'conference_id' => $conference->id,
        ];
    }
}

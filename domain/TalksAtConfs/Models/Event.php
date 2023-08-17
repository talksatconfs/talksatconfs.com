<?php

// declare(strict_types=1);

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Contracts\UuidForModel;
use Domain\TalksAtConfs\Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Actionable;
use Laravel\Scout\Searchable;
use Spatie\Url\Url;

/**
 * Domain\TalksAtConfs\Models\Event
 *
 * @property string $uuid
 * @property int $id
 * @property string $location
 * @property string $slug
 * @property string $playlist
 * @property string $playlist_url
 * @property string $name
 * @property string $city
 * @property string $country
 * @property string $venue
 * @property string $link
 * @property mixed $from_date
 * @property mixed $to_date
 */
class Event extends TacModel
{
    // use Actionable;
    use HasFactory;
    use Notifiable;
    use Searchable;
    use UuidForModel;

    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    protected static function newFactory(): Factory
    {
        return EventFactory::new();
    }

    // Mutators
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getEventTitleAttribute(): string
    {
        return $this->name . ', ' . $this->location . '(' . $this->from_date?->format('M Y') . ')';
    }

    public function getCanonicalUrlAttribute(): string
    {
        return route('events.show', ['event' => $this->slug]);
    }

    public function getPlaylistIdAttribute()
    {
        $playlistData = explode(':', $this->playlist);

        return Arr::get($playlistData, 1);
    }

    public function getPlaylistUrlAttribute()
    {
        $playlistData = explode(':', $this->playlist);

        if (count($playlistData) !== 2) {
            return $this->playlist;
        }

        if (in_array($playlistData[0], ['youtube', 'youtube.com', 'www.youtube.com'])) {
            return 'https://www.youtube.com/playlist?list=' . $playlistData[1];
        }

        if (in_array($playlistData[0], ['vimeo', 'vimeo.com'])) {
            return 'https://vimeo.com/album/' . $playlistData[1];
        }

        if (count($playlistData) === 2) {
            return $playlistData[1];
        }
    }

    public function getPlaylistDisplayUrlAttribute(): Url
    {
        return Url::fromString($this->playlist_url)
            ->withoutQueryParameter('list');
    }

    public function getDisplayLocationAttribute()
    {
        $location = '';
        if (empty($this->city) && empty($this->country)) {
            $location = $this->location;
        } else {
            $location = $this->city . ', ' . $this->country;
        }

        return $location;
    }

    /**
     * @return Url&Url|null
     */
    public function getLinkUrlAttribute()
    {
        if (! empty($this->link)) {
            return Url::fromString($this->link)
                ->withScheme('https');
        }
    }

    public function getFromToDateAttribute(): string
    {
        return $this->from_date->format('M d, Y') . ' to ' . $this->to_date->format('M d, Y');
    }

    // Relationships
    public function conference(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function talks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public function speakers(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Speaker::class, Talk::class);
    }

    public function scopeDetails(Builder $query): void
    {
        $query->select([
            'id', 'name', 'description',
            'slug', 'location', 'link',
            'playlist',
            'from_date', 'to_date',
            'conference_id',
        ])
            // ->with(['tags', 'conference'])
            ->with(['conference'])
            ->withCount('talks')
            ->orderByDesc('from_date');
    }

    public function searchableAs(): string
    {
        return config('app.env') . '_events_index';
    }

    /**
     * @psalm-return array{id: mixed, uuid: mixed, name: mixed, location: mixed, venue: mixed, city: mixed, country: mixed, link: mixed, from_date: mixed, to_date: mixed, conference: mixed, conference_id: mixed}
     */
    public function toSearchableArray(): array
    {
        $conference = $this->conference()->first();

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'location' => $this->location,
            'venue' => $this->venue,
            'city' => $this->city,
            'country' => $this->country,
            'link' => $this->link,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'conference' => $conference->name,
            'conference_id' => $conference->id,
        ];
    }
}

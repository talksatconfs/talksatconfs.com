<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Contracts\UuidForModel;
use Domain\TalksAtConfs\Database\Factories\ConferenceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

/**
 * Domain\TalksAtConfs\Models\Conference
 *
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $twitter
 * @property string $channel
 * @property string $name
 * @property string $description
 * @property string $website
 * @property string $youtube
 */
class Conference extends TacModel
{
    use HasFactory;
    use Notifiable;
    use Searchable;
    use UuidForModel;
    use SoftDeletes;
    use HasTags;

    protected $guarded = [];

    public static function getTagClassName()
    {
        return Tag::class;
    }

    protected static function newFactory(): Factory
    {
        return ConferenceFactory::new();
    }

    // Mutators
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getCanonicalUrlAttribute(): string
    {
        return route('conferences.show', ['conference' => $this->slug]);
    }

    public function getTwitterUrlAttribute()
    {
        return twitterUrl($this->twitter);
    }

    public function getChannelIdAttribute()
    {
        $channelData = explode(':', $this->channel);

        return Arr::get($channelData, 1);
    }

    public function getChannelSourceAttribute()
    {
        return Arr::get(explode(':', $this->channel), '0');
    }

    public function getChannelKeyAttribute()
    {
        return Arr::get(explode(':', $this->channel), '1');
    }

    public function getChannelUrlAttribute()
    {
        if (in_array($this->channelSource, ['youtube', 'vimeo'])) {
            return $this->channelSource === 'youtube'
                ? 'https://www.youtube.com/channel/' . $this->channelKey
                : 'https://vimeo.com/channels/' . $this->channelKey;
        }

        if (Str::startsWith($this->channel, 'UC')) {
            return 'https://www.youtube.com/channel/' . $this->channel;
        }

        return $this->channel;
    }

    // Route
    public function getRoute(): void
    {
    }

    // Relationships
    public function events()
    {
        return $this->hasMany(Event::class)->with('conference');
    }

    public function talks(): HasManyThrough
    {
        return $this->hasManyThrough(Talk::class, Event::class);
    }

    public function tags()
    {
        return $this
            ->morphToMany(self::getTagClassName(), 'taggable', 'taggables', null, 'tag_id')
            ->orderBy('order_column');
    }

    public function scopeDetails(Builder $query): void
    {
        $query->select(['id', 'name', 'slug', 'description', 'website', 'twitter', 'channel'])
            // ->with(['tags'])
            ->withCount('events')
            ->withCount('talks');
    }

    public function searchableAs(): string
    {
        return config('app.env') . '_conferences_index';
    }

    /**
     * @psalm-return array{id: mixed, uuid: mixed, name: mixed, description: mixed, website: mixed, twitter: mixed, youtube: mixed}
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'website' => $this->website,
            'twitter' => $this->twitter,
            'youtube' => $this->youtube,
        ];
    }
}

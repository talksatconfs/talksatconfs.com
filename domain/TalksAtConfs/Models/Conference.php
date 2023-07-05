<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Contracts\UuidForModel;
use Domain\TalksAtConfs\Database\Factories\ConferenceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

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

    protected $guarded = [];

    public static function getTagClassName(): string
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
    protected function canonicalUrl(): Attribute
    {
        return Attribute::make(
            get: function (string|null $value, array $attributes) {
                return route('conferences.show', ['conference' => $attributes['slug']]);
            },
        );
    }

    protected function twitterUrl(): Attribute
    {
        return Attribute::make(
            get: function (string|null $value, array $attributes) {
                return twitterUrl($attributes['twitter']);
            },
        );
    }

    protected function channelId(): Attribute
    {
        return Attribute::make(
            get: function (string|null $value, array $attributes) {
                $channelData = explode(':', $attributes['channel']);

                return Arr::get($channelData, 1);
            },
        );
    }

    protected function channelSource(): Attribute
    {
        return Attribute::make(
            get: function (string|null $value, array $attributes) {
                return Arr::get(explode(':', $attributes['channel']), 0);
            },
        );
    }

    protected function channelKey(): Attribute
    {
        return Attribute::make(
            get: function (string|null $value, array $attributes) {
                return Arr::get(explode(':', $attributes['channel']), '1');
            },
        );
    }

    protected function channelUrl(): Attribute
    {
        return Attribute::make(
            get: function (string|null $value, array $attributes) {
                if (in_array($attributes['channel_source'], ['youtube', 'vimeo'])) {
                    return $attributes['channel_source'] === 'youtube'
                        ? 'https://www.youtube.com/channel/'.$attributes['channel_key']
                        : 'https://vimeo.com/channels/'.$attributes['channel_key'];
                }

                if (Str::startsWith($attributes['channel'], 'UC')) {
                    return 'https://www.youtube.com/channel/'.$attributes['channel'];
                }

                return $attributes['channel'];
            },
        );
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
        return 'conferences_index';
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

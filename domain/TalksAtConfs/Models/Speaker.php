<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Database\Factories\SpeakerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * Domain\TalksAtConfs\Models\Speaker
 *
 * @property string $twitter
 * @property string $github
 * @property string $youtube
 * @property string $uuid
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $bio
 * @property string $website
 */
class Speaker extends AbstractTacModel
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    protected static function newFactory(): Factory
    {
        return SpeakerFactory::new();
    }

    // Mutators
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getSearchAvatarAttribute()
    {
        return $this->github ?? $this->twitter;
    }

    public function getGithubLinkAttribute(): string
    {
        return 'https://github.com/' . $this->github;
    }

    public function getTwitterLinkAttribute(): string
    {
        return 'https://twitter.com/' . $this->twitter;
    }

    public function getYoutubeLinkAttribute(): string
    {
        return 'https://youtube.com/' . $this->youtube;
    }

    public function getCanonicalUrlAttribute(): string
    {
        return route('speakers.show', ['speaker' => $this->slug]);
    }

    public function talks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public function scopeDetails(Builder $query): void
    {
        $query->select(['id', 'name', 'slug', 'website', 'github', 'twitter', 'youtube'])
            ->withCount('talks');
    }

    public function searchableAs(): string
    {
        return 'speakers_index';
    }

    /**
     * @psalm-return array{id: mixed, uuid: mixed, name: mixed, bio: mixed, website: mixed, twitter: mixed, github: mixed}
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'bio' => $this->bio,
            'website' => $this->website,
            'twitter' => $this->twitter,
            'github' => $this->github,
        ];
    }
}

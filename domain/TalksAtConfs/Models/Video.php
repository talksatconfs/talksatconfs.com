<?php

namespace Domain\TalksAtConfs\Models;

use Carbon\CarbonInterval;
use Domain\TalksAtConfs\Contracts\UuidForModel;
use Domain\TalksAtConfs\Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

/**
 * Domain\TalksAtConfs\Models\Video
 *
 * @property string $source
 * @property string $key
 * @property int $duration
 * @property string $uuid
 * @property int $id
 */
class Video extends TacModel
{
    use HasFactory;
    use Searchable;
    use UuidForModel;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'timestamp',
        'duration' => 'integer',
    ];

    protected static function newFactory(): Factory
    {
        return VideoFactory::new();
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function getVideoEmbedLinkAttribute(): ?string
    {
        if (in_array($this->source, ['youtube', 'www.youtube.com'])) {
            return 'https://www.youtube.com/embed/' . $this->key;
        }
        if (in_array($this->source, ['vimeo', 'vimeo.com'])) {
            if (Str::contains($this->key, 'showcase')) {
                return 'https://player.vimeo.com/video/' . Str::after($this->key, 'video/') . '?color=0c88dd&title=0&byline=0&portrait=0&badge=0';
            }

            return 'https://player.vimeo.com/video/' . $this->key . '?color=0c88dd&title=0&byline=0&portrait=0&badge=0';
        }

        return null;
    }

    public function getDurationForHumansAttribute(): string
    {
        return CarbonInterval::seconds($this->duration)->cascade()->forHumans();
    }

    public function videoLink(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => match ($attributes['source']) {
                'youtube', 'www.youtube.com' => 'https://www.youtube.com/watch?v=' . $attributes['key'],
                'vimeo', 'vimeo.com' => 'https://vimeo.com/' . $attributes['key'],
                'halfstackconf.streameventlive.com' => 'https://halfstackconf.streameventlive.com' . $attributes['key'],
                default => $attributes['key']
            }
        );
    }

    public function scopeMissingDetails(Builder $query, $days = 30): void
    {
        $query->where('updated_at', '<', now()->subDays($days))
            ->where(function ($query) {
                $query->orWhereNull('title')
                    ->orWhereNull('description')
                    ->orWhereNull('duration')
                    ->orWhereNull('published_at')
                    ->orWhereNull('channel_id');
            });
    }

    public function scopeOnlyYoutube(Builder $query): void
    {
        $query->where('source', 'youtube');
    }

    public function searchableAs(): string
    {
        return config('app.env') . '_videos_index';
    }

    /**
     * @psalm-return array{id: mixed, uuid: mixed, key: mixed, source: mixed}
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'key' => $this->key,
            'source' => $this->source,
        ];
    }
}

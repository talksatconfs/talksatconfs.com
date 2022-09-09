<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Database\Factories\SlideFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

/**
 * Domain\TalksAtConfs\Models\Slide
 *
 * @property string $link
 * @property string $uuid
 * @property int $id
 */
class Slide extends TacModel
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    protected static function newFactory(): Factory
    {
        return SlideFactory::new();
    }

    public function talk(): BelongsTo
    {
        return $this->belongsTo(Talk::class);
    }

    public function searchableAs(): string
    {
        return 'slides_index';
    }

    /**
     * @psalm-return array{id: mixed, uuid: mixed, link: mixed}
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'link' => $this->link,
        ];
    }
}

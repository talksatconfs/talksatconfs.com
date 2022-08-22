<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Database\Factories\ChannelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Channel extends AbstractTacModel
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    protected static function newFactory(): Factory
    {
        return ChannelFactory::new();
    }

    /**
     * Adds a scope to the Model to query for missing details
     *
     * @param Builder $query //
     *
     * @return void
     */
    public function scopeMissingDetails(Builder $query): void
    {
        $query->where(
            function ($query) {
                $query->orWhereNull('title')
                    ->orWhereNull('description');
            }
        );
    }

    /**
     * Relation with the Video model
     * Can have many videos
     *
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}

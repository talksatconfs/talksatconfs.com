<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Database\Factories\TagFactory;
use Domain\TalksAtConfs\Nova\Resources\Conference;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Tags\Tag as TagsTag;

class Tag extends TagsTag
{
    use HasFactory;

    public function getConnectionName()
    {
        return config('talksatconfs.database.connection');
    }

    protected static function newFactory(): Factory
    {
        return TagFactory::new();
    }

    /**
     * Get all of the conferences that are assigned this tag.
     */
    public function conferences()
    {
        return $this->morphedByMany(Conference::class, 'taggable');
    }
}

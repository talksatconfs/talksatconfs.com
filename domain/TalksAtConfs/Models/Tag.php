<?php

namespace Domain\TalksAtConfs\Models;

use Domain\TalksAtConfs\Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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

    public function conferences(): MorphToMany
    {
        return $this->morphedByMany(Conference::class, 'taggable');
    }
}

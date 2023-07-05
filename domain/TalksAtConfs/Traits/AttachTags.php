<?php

namespace Domain\TalksAtConfs\Traits;

use Domain\TalksAtConfs\Actions\AddTag;

trait AttachTags
{
    public $tagIds;

    protected static function booted()
    {
        $self = new static();

        static::saving(function ($self) {
            $self->tagIds = $self->saveTags($self->tags);
            unset($self->tags);
        });
        static::saved(function ($self): never {
            // dd($self->tagIds);
            $self->tags()->sync($self->tagIds);
        });
    }

    private function saveTags($tags)
    {
        collect($tags)->map(function ($tag) {
            $tag_action = new AddTag();
            $tagModel = $tag_action->handle(['name' => $tag]);

            return $tagModel->id;
        });
    }
}

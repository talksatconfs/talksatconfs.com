<?php

namespace Domain\TalksAtConfs\Actions;

use Domain\TalksAtConfs\Models\Slide;
use Illuminate\Support\Arr;

class AddSlide
{
    public function handle($data)
    {
        $slide = Slide::firstOrNew([
            'talk_id' => Arr::get($data, 'talk_id'),
            'link' => Arr::get($data, 'link'),
        ]);
        $slide->save();

        return $slide;
    }
}

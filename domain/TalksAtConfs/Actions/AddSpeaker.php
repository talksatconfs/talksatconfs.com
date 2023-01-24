<?php

namespace Domain\TalksAtConfs\Actions;

use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AddSpeaker
{
    public function handle($data)
    {
        $speaker = Speaker::firstOrNew([
            'slug' => Str::slug(Arr::get($data, 'name')),
        ]);

        $data = collect($data)
            ->filter(fn ($v) => ! empty($v))->toArray();

        $speaker->fill($data);
        $speaker->save();

        return $speaker;
    }
}

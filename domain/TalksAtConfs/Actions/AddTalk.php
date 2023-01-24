<?php

namespace Domain\TalksAtConfs\Actions;

use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AddTalk
{
    protected $fieldsMap = [
        'title',
    ];

    protected $videoFieldsMap = [

    ];

    protected $slideFieldsMap = [

    ];

    protected $speakerFieldsMap = [

    ];

    public function handle($data)
    {
        $talk = Talk::firstOrNew([
            'slug' => Str::slug(Arr::get($data, 'title')),
            'event_id' => Arr::get($data, 'event_id'),
        ]);

        $data = collect($data)
            ->filter(fn ($v) => ! empty($v))->toArray();

        $talk->fill($data);
        $talk->save();

        return $talk;
    }
}

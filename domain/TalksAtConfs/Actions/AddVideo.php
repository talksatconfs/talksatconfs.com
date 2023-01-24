<?php

namespace Domain\TalksAtConfs\Actions;

use App\Sanitizers\VideoSourceSanitizer;
use Domain\TalksAtConfs\Models\Video;
use Illuminate\Support\Arr;

class AddVideo
{
    public function handle($data)
    {
        $video = Video::firstOrNew([
            'key' => Arr::get($data, 'key'),
            'source' => VideoSourceSanitizer::sanitize(Arr::get($data, 'source')),
        ]);

        $data = collect($data)
            ->filter(fn ($v) => ! empty($v))->toArray();

        $video->fill($data);

        $video->save();

        return $video;
    }
}

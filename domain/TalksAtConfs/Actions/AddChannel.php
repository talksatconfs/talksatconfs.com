<?php

namespace Domain\TalksAtConfs\Actions;

use App\Sanitizers\VideoSourceSanitizer;
use Domain\TalksAtConfs\Models\Channel;
use Illuminate\Support\Arr;

class AddChannel
{
    public function handle($data): Channel
    {
        $channel = Channel::firstOrNew(
            [
                'key' => Arr::get($data, 'key'),
                'source' => VideoSourceSanitizer::sanitize(
                    Arr::get($data, 'source')
                ),
            ]
        );

        $data = collect($data)
            ->filter(
                function ($v) {
                    return ! empty($v);
                }
            )->toArray();

        $channel->fill($data);

        $channel->save();

        return $channel;
    }
}

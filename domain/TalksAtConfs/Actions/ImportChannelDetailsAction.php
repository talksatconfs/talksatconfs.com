<?php

namespace Domain\TalksAtConfs\Actions;

use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ImportChannelDetailsAction
{
    private function getYoutubeResponse($id)
    {
        return Cache::remember(
            'channel-'.$id,
            now()->addWeek(),
            fn () => Youtube::getChannelById($id)
        );
    }

    private function processResponse($result)
    {
        $channel = (new AddChannel())->handle(
            [
                'source' => 'youtube',
                'key' => Arr::get($result, 'id'),
                'title' => Arr::get($result, 'snippet.title'),
                'description' => Arr::get($result, 'snippet.description'),
                'custom_url' => Arr::get($result, 'snippet.customUrl'),
            ]
        );

        return $channel->id;
    }

    public function handle($id): void
    {
        $response = $this->getYoutubeResponse($id);
        $response = json_decode(json_encode($response, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        if (! empty(Arr::get($response, 'snippet.title'))) {
            $this->processResponse($response);
        }
    }
}

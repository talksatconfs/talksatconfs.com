<?php

namespace Domain\TalksAtConfs\Actions;

use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ImportYoutubeVideosAction
{
    private function getYoutubeResponse($type, $id)
    {
        return Cache::remember($type.'-'.$id, now()->addDay(), fn () => Youtube::getPlaylistItemsByPlaylistId($id));
    }

    private function processResponse($results): void
    {
        collect($results)
            ->each(function ($item) {
                $item = json_decode(json_encode($item, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

                $videoKey = Arr::get($item, 'snippet.resourceId.videoId') ?? Arr::get($item, 'contentDetails.videoId');

                $video = (new AddVideo())->handle([
                    'key' => $videoKey,
                    'source' => 'youtube',
                ]);

                return $video->id;
            });
    }

    public function handle($type, $id): void
    {
        if ($type === 'playlist') {
            $response = $this->getYoutubeResponse($type, $id);
            if (Arr::get($response, 'info.totalResults') > 0) {
                $this->processResponse(Arr::get($response, 'results'));
            }
        }
    }
}

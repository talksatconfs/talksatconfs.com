<?php

namespace Domain\TalksAtConfs\Actions;

use Alaouy\Youtube\Facades\Youtube;
use App\Sanitizers\VideoTimeSanitizer;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class ImportVideoDetailsAction
{
    private function getYoutubeResponse(array $ids)
    {
        return Cache::remember(
            'video-' . implode('-', $ids),
            now()->addDay(),
            fn () => Youtube::getVideoInfo($ids)
        );
    }

    private function processResponse(array $results): void
    {
        collect($results)
            ->each(function ($item) {
                $item = json_decode(json_encode($item, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

                $channel = (new AddChannel())->handle([
                    'source' => 'youtube',
                    'key' => Arr::get($item, 'snippet.channelId'),
                ]);

                $channel_id = $channel->id ?? null;

                $video = (new AddVideo())->handle([
                    'key' => Arr::get($item, 'id'),
                    'source' => 'youtube',
                    'title' => Arr::get($item, 'snippet.title'),
                    'description' => Arr::get($item, 'snippet.description'),
                    'channel_id' => $channel_id,
                    'published_at' => Carbon::parse(Arr::get($item, 'snippet.publishedAt')),
                    'duration' => VideoTimeSanitizer::sanitize(Arr::get($item, 'contentDetails.duration')),
                ]);

                return $video->id;
            });
    }

    public function handle(array $ids): void
    {
        $response = $this->getYoutubeResponse($ids);
        if ((is_countable($response) ? count($response) : 0) > 0) {
            $this->processResponse($response);
        }
    }
}

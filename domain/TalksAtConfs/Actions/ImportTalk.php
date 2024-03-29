<?php

namespace Domain\TalksAtConfs\Actions;

use App\Sanitizers\VideoSourceSanitizer;
use Illuminate\Support\Arr;
use Spatie\Url\Url;

class ImportTalk
{
    public function __construct(protected $event_id, protected $data)
    {
    }

    public function handle(): void
    {
        $talk = (new AddTalk())->handle([
            'event_id' => Arr::get($this->data, 'event_id'),
            'title' =>  Arr::get($this->data, 'title'),
            'talk_date' =>  Arr::get($this->data, 'talk_date'),
            'link' =>  Arr::get($this->data, 'link'),
            'description' =>  Arr::get($this->data, 'description'),
        ]);

        // syncing speakers
        $speakers = $this->syncSpeakers();
        $talk->speakers()->sync($speakers->toArray());

        // syncing videos
        $this->syncVideos($talk);

        // syncing slides
        $this->syncSlides($talk);
    }

    private function syncSpeakers()
    {
        $speakerAction = new AddSpeaker();

        return collect($this->getSpeakerNames(Arr::get($this->data, 'speakers')))
            ->map(fn ($speaker) => $speakerAction->handle(['name' => $speaker])->id);
    }

    private function syncVideos($talk)
    {
        if (! empty(Arr::get($this->data, 'talk_video'))) {
            $videoMeta = $this->getVideoMeta(Arr::get($this->data, 'talk_video'));
            $video = (new AddVideo())->handle([
                'key' => Arr::get($videoMeta, 'key'),
                'source' => Arr::get($videoMeta, 'source'),
            ]);
            $talk->videos()->sync([$video->id]);
        }
    }

    private function syncSlides($talk)
    {
        if (! empty(Arr::get($this->data, 'talk_slide'))) {
            (new AddSlide())->handle([
                'talk_id' => $talk->id,
                'link' => Arr::get($this->data, 'talk_slide'),
            ]);
        }
    }

    private function getVideoMeta($url): array
    {
        $urlObj = Url::fromString($url);

        $key = $this->getVideoKey($urlObj);

        return [
            'source' => VideoSourceSanitizer::sanitize($urlObj->getHost()),
            'key' => $key,
        ];
    }

    private function getVideoKey(Url $url): string
    {
        if (str_contains($url->getHost(), 'yout')) {
            return $url->getQueryParameter('v');
        }

        return $url->getPath();
    }

    private function getSpeakerNames($speakers): array
    {
        return explode('&', (string) $speakers);
    }
}

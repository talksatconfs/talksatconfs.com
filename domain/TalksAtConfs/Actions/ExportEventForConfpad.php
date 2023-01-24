<?php

namespace Domain\TalksAtConfs\Actions;

use Domain\TalksAtConfs\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ExportEventForConfpad
{
    final public const CONF_DIR_NAME = './conferences/';

    public function __construct(protected $event_id)
    {
    }

    public function handle(): void
    {
        $event = Event::with(['conference', 'talks'])->where('id', '=', $this->event_id)->first();

        $yamlData = [];

        $talkData = [];

        $talkData = $event->talks->map(fn ($talk) => [
            'title' => $talk->title,
            'lang' => 'en',
            'type' => 'regular',
            'time' => Str::replace(' 00:00:00', '', $talk->talk_date->format('Y-m-d H:i:s')),
            'authors' => $this->getAuthors($talk),
            'slides' => $this->getSlides($talk),
            'videos' => $this->getVideos($talk),
            'description' => $talk->description,
        ])->toArray();

        $yamlData['conference'] = [
            'name' => $event->name,
            'series' => $event->conference->link,
            'tags' => $event->conference->tags->pluck('name')->all(),
            'links' => [
                'playlist' => $event->playlist_url,
                'twitter' => $event->conference->twitter,
                'youtube' => $event->conference->channel,
                'website' => $event->link,
            ],
            'date' => [
                'from' => $event->from_date->format('Y-m-d'),
                'to' => $event->to_date->format('Y-m-d'),
            ],
            'location' => [
                'country' => $event->country,
                'city' => $event->city,
            ],
            'description' => $event->description,
            'talks' => $talkData,
        ];

        $filePath = './' . self::CONF_DIR_NAME . '/' . $event->from_date->format('Y') . '/' . $event->from_date->format('Y-m-d') . '-' . $event->slug . '.yaml';

        Storage::disk('confpad-data')
            ->put(
                $filePath,
                Yaml::dump(
                    $yamlData,
                    5,
                    2,
                )
            );
    }

    private function getAuthors($talk)
    {
        return $talk
            ->speakers()
            ->get()
            ->mapWithKeys(fn ($speaker) => [
                'name' => $speaker->name,
                'twitter' => $speaker->twitter,
                'github' => $speaker->github,
                'website' => $speaker->website,
            ])
            ->toArray();
    }

    private function getSlides($talk)
    {
        return $talk
            ->slides()
            ->get()
            ->map(fn ($slide) => $slide->link)
            ->toArray();
    }

    private function getVideos($talk)
    {
        return $talk
            ->videos()
            ->get()
            ->map(fn ($video) => $video->video_link)
            ->toArray();
    }
}

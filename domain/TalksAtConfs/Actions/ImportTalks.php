<?php

namespace Domain\TalksAtConfs\Actions;

use Carbon\Carbon;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ImportTalks
{
    private $videoHostMap = [
        'youtu.be' => 'youtube',
        'youtube.com' => 'youtube',
    ];

    public function __construct(protected $event_id, protected $year, protected $filename)
    {
    }

    public function handle(): void
    {
        $confData = (new Yaml())->parse(
            Storage::disk('confpad-data')
            ->get('/conferences/' . $this->year . '/' . $this->filename)
        );

        collect($confData['talks'])->each(function ($talkData) {
            $talkAttrs = collect($talkData)
                ->only(['title', 'time', 'description'])
                ->filter(function ($v) {
                    return ! empty($v);
                })
                ->toArray();
            if ($talkAttrs['time'] ?? false) {
                $talkAttrs['talk_date'] = Carbon::parse($talkAttrs['time']);
                unset($talkAttrs['time']);
            }
            $talkAttrs['event_id'] = $this->event_id;

            $talk = Talk::firstOrNew([
                'event_id' => Arr::get($talkAttrs, 'event_id'),
                'slug' => Str::slug(Arr::get($talkAttrs, 'title')),
            ]);
            $talk->fill($talkAttrs);
            $talk->save();

            $slideAction = new AddSlide();
            collect($talkData['slides'])
                ->map(function ($slide) use ($slideAction, $talk) {
                    $slideAttrs = [
                        'talk_id' => $talk->id,
                        'link' => $slide,
                    ];

                    return $slideAction->handle($slideAttrs)->id;
                });

            $videoAction = new AddVideo();
            $videos = collect($talkData['videos'])
                ->map(function ($video) use ($videoAction) {
                    $arrUrl = parse_url($video);
                    $source = Arr::get($arrUrl, 'host');
                    if (isset($this->videoHostMap[$source])) {
                        $vidAttrs = [
                            'key' => Str::after(Arr::get($arrUrl, 'path'), '/'),
                            'source' => $this->videoHostMap[$source],
                        ];
                    } else {
                        $vidAttrs = [
                            'key' => $video,
                            'source' => $source,
                        ];
                    }

                    return $videoAction->handle($vidAttrs)->id;
                });
            $talk->videos()->sync($videos);

            $speakerAction = new AddSpeaker();
            $speakers = collect($talkData['authors'])
                ->map(function ($speaker) use ($speakerAction) {
                    return $speakerAction->handle($speaker)->id;
                });
            $talk->speakers()->sync($speakers);
        });
    }
}

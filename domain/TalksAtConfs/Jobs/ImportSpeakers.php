<?php

namespace Domain\TalksAtConfs\Jobs;

use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

class ImportSpeakers implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public const SPEAKERS_FILE_NAME = 'speakers.yml';

    public function __construct()
    {
        //
    }

    private function addSpeakerWithData($speakerData): void
    {
        $speaker = Speaker::firstOrNew([
            'name' => Arr::get($speakerData, 'name'),
        ]);
        $speaker->bio = Arr::get($speakerData, 'description');
        $speaker->website = Arr::get($speakerData, 'website');
        $speaker->twitter = Arr::get($speakerData, 'twitter');
        $speaker->github = Arr::get($speakerData, 'github');
        $speaker->youtube = Arr::get($speakerData, 'youtube');
        $speaker->save();
    }

    public function handle(): void
    {
        collect(
            (new Yaml())
                ->parse(
                    Storage::disk('tac-data')
                        ->get(self::SPEAKERS_FILE_NAME)
                )
        )
        ->each(function ($speaker) {
            $this->addSpeakerWithData($speaker);
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ImportSpeakers;
use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Slide;
use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use Domain\TalksAtConfs\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MeiliSearch\Exceptions\ApiException;
use Symfony\Component\Yaml\Yaml;

class ImportConferences extends Command
{
    public const CONF_DIR_NAME = 'conferences';

    public const META_CONF_FILE_NAME = '_meta.yml';

    public const SPEAKERS_FILE_NAME = 'speakers.yml';

    public const DBREPO_PATH = '/Users/swapnil.s/code/codeat3/tac-db';

    protected $disk;

    protected $signature = 'cat3:tac-import';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->disk = Storage::disk('talksatconfs-data');
    }

    private function getConferenceFilePath($conference): string
    {
        return $conference.DIRECTORY_SEPARATOR;
    }

    private function getSpeakerIds($speakers = []): \Illuminate\Support\Collection
    {
        // $this->info('Adding speakers: ');

        return collect($speakers)->map(function ($speaker) {
            $speaker = Speaker::firstOrNew([
                'name' => $speaker,
            ]);
            $speaker->save();

            return $speaker->id;
        });
    }

    private function getVideoIds($videos = []): \Illuminate\Support\Collection
    {
        // $this->info('Adding videos: ');

        return collect($videos)->map(function ($key) {
            if ($key !== '999999999') {
                $videoData = explode(':', $key);
                $key = Arr::get($videoData, 1) ?? 'youtube';
                $source = Arr::get($videoData, 0);
                $video = Video::firstOrNew([
                    'key' => $key,
                    'source' => $source,
                ]);
                $video->save();

                return $video->id;
            }
        });
    }

    private function getSlideIds($talk, $slides = []): \Illuminate\Support\Collection
    {
        // $this->info('Adding sldies: ');
        return collect($slides)->map(function ($link) use ($talk) {
            $slide = Slide::firstOrNew([
                'link' => $link,
                'talk_id' => $talk->id,
            ]);
            $slide->save();

            return $slide->id;
        });
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

    private function getEvent($eventData, $conference): Event
    {
        $event = new Event();
        $event->name = Arr::get($eventData, 'name');
        $event->description = Arr::get($eventData, 'description');
        $event->location = Arr::get($eventData, 'location');
        $event->venue = Arr::get($eventData, 'venue');
        $event->city = Arr::get($eventData, 'city');
        $event->country = Arr::get($eventData, 'country');
        $event->link = Arr::get($eventData, 'link');
        $event->playlist = Arr::get($eventData, 'playlist');
        $event->from_date = Carbon::parse(Arr::get($eventData, 'from_date'));
        $event->to_date = Carbon::parse(Arr::get($eventData, 'to_date'));

        $event->conference_id = $conference->id;

        $event->save();

        $eventTags = (! empty(Arr::get($eventData, 'tags'))) ? Arr::get($eventData, 'tags') : [];
        $event->syncTags($eventTags);

        return $event;
    }

    private function getTalk($event, $talkData): Talk
    {
        // $this->info('Adding talk: '.Arr::get($talkData, 'title').':START');
        $talk = new Talk();

        $talk->event_id = $event->id;
        $talk->title = Arr::get($talkData, 'title');
        $talk->description = Arr::get($talkData, 'description');
        $talk->link = Arr::get($talkData, 'link');
        $talk->talk_date = Arr::get($talkData, 'talk_date');

        $talk->save();

        $talkTags = (! empty(Arr::get($talkData, 'tags'))) ? Arr::get($talkData, 'tags') : [];
        $talk->syncTags($talkTags);

        return $talk;
    }

    private function emptyAllTable(): void
    {
        $this->info('Truncating Tables:START');
        Conference::truncate();
        Event::truncate();
        Talk::truncate();
        Speaker::truncate();
        Video::truncate();
        Slide::truncate();
        DB::table('talk_video')->truncate();
        $this->info('Truncating Tables:END');
    }

    private function deleteAllIndexes(): void
    {
        $this->info('Deletng meilisearch data:START');
        $models = [
            Conference::class,
            Event::class,
            Talk::class,
            Speaker::class,
            Video::class,
            Slide::class,
        ];

        collect($models)->each(function ($class) {
            $this->info('flushing data '.$class.':START');

            try {
                Artisan::call('scout:flush', [
                    'model' => $class,
                ]);
            } catch (ApiException $e) {
                $this->warn('FLUSH NOT FOUND'.$e->getMessage());
            }
            $this->info('flushing data '.$class.':END');
        });

        collect($models)->each(function ($class) {
            $this->info('deleting index '.$class.':START');

            Artisan::call('scout:delete-index', [
                'name' => Str::plural(Str::lower(Str::afterLast($class, '\\'))).'_index',
            ]);

            $this->info('deleting index '.$class.':end');
        });

        $this->info('Deletng meilisearch data:END');
    }

    private function convert($size): string
    {
        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).' '.$unit[$i];
    }

    protected function addSpeakerData(): void
    {
        $this->info('Loading Speakers:START');

        ImportSpeakers::dispatch();

        $this->info('Loading Speakers:END');
    }

    public function handle(): void
    {
        Artisan::call('backup:run');
        Log::info('DB Backup Done before import');
        $this->info('Memory Usage: '.$this->convert(memory_get_usage()));
        $this->info('TalksAtConfs:: Start');
        $this->info('Memory Usage: '.$this->convert(memory_get_usage()));
        $this->emptyAllTable();
        $this->info('Memory Usage: '.$this->convert(memory_get_usage()));
        $this->deleteAllIndexes();

        $this->info('Memory Usage: '.$this->convert(memory_get_usage()));
        $this->addSpeakerData();
        $this->info('Memory Usage: '.$this->convert(memory_get_usage()));

        $this->info('Loading Conferences');
        $this->withProgressBar(collect($this->disk->allDirectories(self::CONF_DIR_NAME)), function ($conference) {
            $this->info('Memory Usage: '.$this->convert(memory_get_usage()));
            $confDir = $this->getConferenceFilePath($conference);
            $confData = (new Yaml())->parse($this->disk->get($confDir.self::META_CONF_FILE_NAME));

            $conference = new Conference();
            $conference->name = Arr::get($confData, 'name');
            $conference->description = Arr::get($confData, 'description');
            $conference->website = Arr::get($confData, 'website');
            $conference->twitter = Arr::get($confData, 'twitter');
            $conference->channel = Arr::get($confData, 'channel');
            $confTags = (! empty(Arr::get($confData, 'tags'))) ? Arr::get($confData, 'tags') : [];
            $conference->save();
            $conference->syncTags($confTags);

            $this->info('Importing Events for: '.$conference->name);
            collect($this->disk->allFiles($confDir))
                ->each(function ($eventFile) use ($conference) {
                    if (! Str::contains($eventFile, self::META_CONF_FILE_NAME)) {
                        $this->info('Memory Usage: '.$this->convert(memory_get_usage()));
                        $eventData = (new Yaml())->parse($this->disk->get($eventFile));

                        $event = $this->getEvent($eventData, $conference);

                        $this->info('Importing Talks for: '.$event->name);
                        if ($talks = Arr::get($eventData, 'talks') ?? false) {
                            foreach ($talks as $talkData) {
                                $talk = $this->getTalk($event, $talkData);

                                // attach speakers
                                $speakerIds = $this->getSpeakerIds(Arr::get($talkData, 'speakers'));
                                $talk->speakers()->sync($speakerIds);

                                $videoIds = $this->getVideoIds(Arr::get($talkData, 'videos', []));
                                $talk->videos()->sync($videoIds);

                                $this->getSlideIds($talk, Arr::get($talkData, 'slides', []));
                            }
                        }
                    }
                });
        });

        $this->info('TalksAtConfs:: End');
    }
}

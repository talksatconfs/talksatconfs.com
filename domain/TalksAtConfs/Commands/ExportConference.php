<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Speaker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class ExportConference extends Command
{
    public const CONF_DIR_NAME = 'conferences';

    public const SUB_DIR = '';

    public const META_CONF_FILE_NAME = '_meta.yml';

    public const SPEAKERS_FILE_NAME = 'speakers.yml';

    protected $disk;

    protected $signature = 'cat3:tac-export';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->disk = Storage::disk('talksatconfs-data');
    }

    private function setAuthors(): void
    {
        $this->disk->delete(self::SPEAKERS_FILE_NAME);

        $speakerData = [];
        Speaker::chunk(10, function ($authors) use (&$speakerData) {
            foreach ($authors as $author) {
                $speakerData[] = $author->only([
                    'name',
                    'bio',
                    'website',
                    'twitter',
                    'github',
                    'youtube',
                ]);
            }
        });

        $this->disk->put(
            self::SPEAKERS_FILE_NAME,
            Yaml::dump($speakerData, 5, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
        );
    }

    /**
     * @return string[]
     *
     * @psalm-return array{0: 'git', 1: '-C', 2: string}
     */
    private function getProcessPayLoad($command): array
    {
        $prepend = [
            'git',
            '-C',
            storage_path('app/talksatconfs-data'),
        ];
        $this->info('prepending: '.implode(' ', $prepend));

        return array_merge($prepend, explode(' ', $command));
    }

    private function execProcess($command): void
    {
        $process = new Process($this->getProcessPayLoad($command));
        $process->start();
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo "\nRead from stdout: ".$data;
            } else { // $process::ERR === $type
                echo "\nRead from stderr: ".$data;
            }
        }
    }

    public function handle(): void
    {
        $timestamp = now()->format('YmdH');

        $this->info('GIT - Checking out to main ');
        $this->execProcess('checkout main');

        sleep(1);

        $this->info('GIT - Prune');
        $this->execProcess('fetch origin --prune');

        sleep(1);

        $this->info('GIT - Pull the changes ');
        $this->execProcess('pull');

        sleep(1);

        $this->info('GIT - Reset Hard ');
        $this->execProcess('reset --hard');

        sleep(1);

        $this->info('GIT - Clean ');
        $this->execProcess('clean -df');

        sleep(1);

        $this->info('GIT - Checking out as '.$timestamp);
        $this->execProcess('checkout -b '.$timestamp);

        sleep(1);

        $this->info('Deleting all directory');
        collect($this->disk->allDirectories())->each(function ($dir) {
            $this->disk->deleteDirectory($dir);
        });

        $this->info('Setting all authors: start');
        $this->setAuthors();

        $this->info('Setting all authors: end');

        Conference::chunk(10, function ($conferences) {
            foreach ($conferences as $conference) {
                $confTags = $conference->tags->pluck('name')->all();
                $confData = [
                    'name' => $conference->name,
                    'description' => $conference->description ?? '',
                    'website' => $conference->website ?? '',
                    'twitter' => $conference->twitter ?? '',
                    'channel' => $conference->channel ?? '',
                    'tags' => count($confTags) ? $confTags : '',
                ];

                $confDir = './'.self::CONF_DIR_NAME.'/'.$conference->slug;

                $this->info('Creating directory: '.$confDir);
                $this->disk->makeDirectory($confDir);
                $this->disk
                    ->put(
                        $confDir.'/'.self::META_CONF_FILE_NAME,
                        Yaml::dump($confData, 5, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
                    );
                $conference->events()->each(function ($event) use ($confDir) {
                    $eventData = [];
                    $eventData['name'] = $event->name;
                    $eventData['description'] = $event->description ?? '';
                    $eventData['location'] = $event->location ?? '';
                    $eventData['venue'] = $event->venue ?? '';
                    $eventData['city'] = $event->city ?? '';
                    $eventData['country'] = $event->country ?? '';
                    $eventData['link'] = $event->link ?? '';
                    $eventData['playlist'] = $event->playlist ?? '';
                    $eventData['from_date'] = $event->from_date?->format('Y-m-d');
                    $eventData['to_date'] = $event->to_date?->format('Y-m-d');
                    $eventTags = []; //$event->tags->pluck('name')->all();
                    $eventData['tags'] = count($eventTags) ? $eventTags : '';

                    $talks = [];
                    $event->talks()->get()->each(function ($talk) use (&$talks) {
                        $videos = $talk->videos()
                            ->get()
                            ->map(function ($video) {
                                return $video->source.':'.$video->key;
                            })
                            ->all();

                        $videos = count($videos) ? $videos : null;

                        $slides = $talk->slides()->get()->pluck('link')->all();
                        $slides = count($slides) ? $slides : null;

                        // $authors = $talk->speakers()->get()->pluck('name')->all();
                        // $authors = count($authors) ? $authors : null;

                        $authors = $talk->speakers()->get()->map(function ($speaker) {
                            return [
                                'name' => $speaker->name,
                                'twitter' => $speaker->twitter,
                                'github' => $speaker->github,
                            ];
                        })->toArray();

                        $talkTags = []; //$talk->tags->pluck('name')->all();
                        $talks[] = [
                            'title' => $talk->title,
                            'description' => '', //$talk->description ??
                            'tags' => count($talkTags) ? $talkTags : '',
                            'link' => $talk->link ?? '',
                            'talk_date' => $talk->talk_date?->format('Y-m-d H:i'),
                            'speakers' => $authors,
                            'videos' => $videos,
                            'slides' => $slides,

                        ];
                    });

                    $eventData['talks'] = $talks;

                    $this->info('Creating event: '.$confDir.'/'.$event->slug.'.yml');
                    $this->disk
                        ->put(
                            $confDir.'/'.$event->slug.'.yml',
                            Yaml::dump($eventData, 5, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
                        );
                });
            }
        });

        sleep(1);

        $this->info('GIT - Adding all files.');
        $this->execProcess('add -A');

        sleep(1);

        $this->info('GIT - Committing all files.');
        $commitMessage = 'commit -m updated-from-db-on-'.$timestamp;
        $this->execProcess($commitMessage);

        sleep(1);

        $this->info('GIT - Pushing to origin '.$timestamp);
        $this->execProcess('push --set-upstream origin '.$timestamp);
    }
}

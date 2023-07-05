<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Domain\TalksAtConfs\Jobs\ImportYoutubeVideosFromChannelJob;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;

class ImportYoutubeVideosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:import-youtube-videos {type?} {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importing videos from youtube (playlist or video id)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(Factory $validator): int
    {
        $data = $this->validData($validator);

        ImportYoutubeVideosFromChannelJob::dispatch($data['id']);

        $this->info("Playlist {$data['id']} added successfully.");

        return 0;
    }

    private function validData(Factory $validator): array
    {
        return $validator->make($this->data(), [
            'type' => ['required', Rule::in(['playlist', 'video'])],
            'id' => ['required', 'string'],
        ])->validate();
    }

    private function data(): array
    {
        return [
            'type' => $this->argument('type') ?? $this->anticipate('What is the type?', ['playlist', 'video']),
            'id' => $this->argument('id') ?? $this->ask('Enter the id'),
        ];
    }
}

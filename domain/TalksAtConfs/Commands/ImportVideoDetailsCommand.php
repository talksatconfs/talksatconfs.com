<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Domain\TalksAtConfs\Jobs\ImportVideoDetailsJob;
use Domain\TalksAtConfs\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;

class ImportVideoDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:import-video-details {id?} {days?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     *
     * @return int
     */
    public function handle(Factory $validator)
    {
        $data = $this->validData($validator);

        if (is_null($data['id'])) {
            // fetch details of all videos
            $days = Arr::get($data, 'days', 30);
            Video::select('key')
                ->missingDetails($days)
                ->onlyYoutube()
                ->chunk(
                    3,
                    function ($chunks) {
                        ImportVideoDetailsJob::dispatch(
                            $chunks->pluck(['key'])->toArray()
                        );
                    }
                );
        } else {
            $vids = explode(',', $data['id']);
            collect($vids)->chunk(3)->each(
                function ($chunk) {
                    ImportVideoDetailsJob::dispatch($chunk->toArray());
                }
            );
        }

        $this->info("Playlist {$data['id']} added successfully.");

        return 0;
    }

    private function validData(Factory $validator): array
    {
        return $validator->make(
            $this->data(),
            [
                'id' => ['string', 'nullable'],
                'days' => ['string', 'nullable'],
            ]
        )->validate();
    }

    private function data(): array
    {
        return [
            'id' => $this->argument('id') ?? $this->ask('Enter the id'),
            'days' => $this->argument('days') ?? $this->ask('Enter the days'),
        ];
    }
}

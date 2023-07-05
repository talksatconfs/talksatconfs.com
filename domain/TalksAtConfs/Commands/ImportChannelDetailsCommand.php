<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ImportChannelDetailsJob;
use Domain\TalksAtConfs\Models\Channel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Factory;

class ImportChannelDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:import-channel-details {id?}';

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
            Channel::select('key')
                ->missingDetails()
                ->where('source', '=', 'youtube')
                ->chunk(
                    1,
                    function ($chunks) {
                        ImportChannelDetailsJob::dispatch(
                            $chunks->first()->key
                        );
                    }
                );
        } else {
            $channels = explode(',', $data['id']);
            collect($channels)->each(
                function ($item) {
                    ImportChannelDetailsJob::dispatch(
                        $item
                    );
                }
            );
        }

        $this->info('Channel Details update job queued successfully.');

        return 0;
    }

    private function validData(Factory $validator): array
    {
        return $validator->make(
            $this->data(),
            [
                'id' => ['string', 'nullable'],
            ]
        )->validate();
    }

    private function data(): array
    {
        return [
            'id' => $this->argument('id') ?? $this->ask('Enter the id'),
        ];
    }
}

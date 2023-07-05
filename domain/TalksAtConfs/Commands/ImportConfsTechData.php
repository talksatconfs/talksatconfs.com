<?php

namespace App\Console\Commands;

use Domain\Misc\Models\ConfsTech;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class ImportConfsTechData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:import-confs-tech';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

    private function getProcessPayLoad($command): array
    {
        $prepend = [
            'git',
            '-C',
            storage_path('app/confs-tech-conference-data'),
        ];
        $this->info('prepending: '.implode(' ', $prepend));

        return array_merge($prepend, explode(' ', $command));
    }

    public function handle(): int
    {
        // fetch from upstream
        $this->info('GIT - Checkout to main branch');
        $this->execProcess('checkout main');

        sleep(1);

        // fetch from upstream
        $this->info('GIT - Fetching from upstream');
        $this->execProcess('fetch upstream');

        sleep(1);

        // reset hard with the latest data from upstream
        $this->info('GIT - Reset from upstream/main');
        $this->execProcess('reset --hard upstream/main');

        sleep(1);

        // git push
        $this->info('GIT - Push to the forked repo');
        $this->execProcess('push origin main');

        sleep(1);

        $this->withProgressBar(collect(Storage::disk('confs-tech-data')->allFiles('./conferences')), function ($file) {
            collect(json_decode(Storage::disk('confs-tech-data')->get($file), true))
                ->each(function ($data) {
                    $data = collect($data)->only([
                        'name',
                        'url',
                        'startDate',
                        'endDate',
                        'city',
                        'country',
                        'twitter',
                    ])->toArray();

                    $event = ConfsTech::firstOrNew($data);

                    $data = collect($data)
                        ->filter(function ($v) {
                            return ! empty($v);
                        })->toArray();

                    $data['category'] = Str::before(
                        Str::afterLast(
                            'conferences/2014/ruby.json',
                            '/'
                        ),
                        '.'
                    );
                    $data['online'] = $data['online'] ?? false;

                    $event->fill($data);
                    $event->save();
                });
        });

        // collect(Storage::disk('confs-tech-data')->allFiles('./conferences'))
        //     ->each(function ($file) {

        //     });
        return 0;
    }
}

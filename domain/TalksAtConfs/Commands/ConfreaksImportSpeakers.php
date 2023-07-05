<?php

namespace App\Console\Commands;

use App\Console\Exceptions\ConfreaksApiFailedException;
use Domain\Misc\Models\ConfreaksSpeaker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfreaksImportSpeakers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:confreaks:import-speakers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $presenterApiUri = 'https://confreaks.tv/api/v1/presenters.json';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (
            Cache::has($this->signature)
            && Cache::get($this->signature) === 'true'
        ) {
            Log::info('skipping the import: '.$this->signature);

            return 0;
        }

        try {
            $presenters = Cache::remember(Str::slug($this->presenterApiUri), now()->addDays(7), function () {
                $response = Http::accept('application/json')->get($this->presenterApiUri);
                if ($response->successful()) {
                    return $response->json();
                } else {
                    throw new ConfreaksApiFailedException('Api call failed:'.$this->presenterApiUri.' with status code:'.$response->status());
                }
            });

            $this->withProgressBar(collect($presenters), function ($presenter) {
                $presenter = ConfreaksSpeaker::firstOrNew($presenter);

                $data = collect($presenter)
                    ->filter(function ($v) {
                        return ! empty($v);
                    })->toArray();

                $presenter->fill($data);
                $presenter->save();
            });

            Cache::put($this->signature, 'true', now()->addDay());
        } catch (ConfreaksApiFailedException $ce) {
            Log::error('failed to import: '.$this->signature);
            Log::error($ce->getMessage());
        }

        return 0;
    }
}

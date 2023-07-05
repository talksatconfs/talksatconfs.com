<?php

namespace namespace Domain\TalksAtConfs\Commands;

use App\Console\Exceptions\ConfreaksApiFailedException;
use Domain\Misc\Models\ConfreaksConference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfreaksImportConferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:confreaks:import-conf';

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
    protected $confApiUri = 'https://confreaks.tv/api/v1/conferences.json';

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
            Log::info('skipping the import: ' . $this->signature);

            return 0;
        }

        try {
            $conferences = Cache::remember(Str::slug($this->confApiUri), now()->addDays(7), function () {
                $response = Http::accept('application/json')->get($this->confApiUri);
                if ($response->successful()) {
                    return $response->json();
                } else {
                    throw new ConfreaksApiFailedException('Api call failed:' . $this->confApiUri . ' with status code:' . $response->status());
                }
            });

            $this->withProgressBar(collect($conferences), function ($conference) {
                $conference = ConfreaksConference::firstOrNew($conference);

                $data = collect($conference)
                    ->filter(function ($v) {
                        return ! empty($v);
                    })->toArray();

                $conference->fill($data);
                $conference->save();
            });
            Cache::put($this->signature, 'true', now()->addDay());
        } catch (ConfreaksApiFailedException $ce) {
            Log::error('failed to import: ' . $this->signature);
            Log::error($ce->getMessage());
        }

        return 0;
    }
}

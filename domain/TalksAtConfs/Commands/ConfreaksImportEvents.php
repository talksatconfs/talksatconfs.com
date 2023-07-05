<?php

namespace App\Console\Commands;

use App\Console\Exceptions\ConfreaksApiFailedException;
use Domain\Misc\Models\ConfreaksEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfreaksImportEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:confreaks:import-events';

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
    protected $eventApiUrl = 'https://confreaks.tv/api/v1/events.json';

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
            $events = Cache::remember(Str::slug($this->eventApiUrl), now()->addDays(7), function () {
                $response = Http::accept('application/json')->get($this->eventApiUrl);
                if ($response->successful()) {
                    return $response->json();
                } else {
                    throw new ConfreaksApiFailedException('Api call failed:'.$this->eventApiUrl.' with status code:'.$response->status());
                }
            });

            $this->withProgressBar(collect($events), function ($event) {
                $event = ConfreaksEvent::firstOrNew($event);

                $data = collect($event)
                    ->filter(function ($v) {
                        return ! empty($v);
                    })->toArray();

                $event->fill($data);
                $event->save();
            });
            Cache::put($this->signature, 'true', now()->addDay());
        } catch (ConfreaksApiFailedException $ce) {
            Log::error('failed to import: '.$this->signature);
            Log::error($ce->getMessage());
        }

        return 0;
    }
}

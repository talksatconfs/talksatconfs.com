<?php

namespace App\Console\Commands;

use App\Console\Exceptions\ConfreaksApiFailedException;
use Domain\Misc\Models\ConfreaksEvent;
use Domain\Misc\Models\ConfreaksSpeaker;
use Domain\Misc\Models\ConfreaksTalk;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfreaksImportEventsTalks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat3:confreaks:import-event-talks';

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
    protected $eventTalksUri = 'https://confreaks.tv/api/v1/events/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ConfreaksEvent::whereNull('processed_at')
            ->orderBy('id')
            ->get()
            ->each(function ($event) {
                $eventTalksUrl = $this->eventTalksUri.$event->short_code.'/videos.json';
                $this->newLine(2);
                $this->info('Gettings talks from: '.$eventTalksUrl);
                try {
                    $talks = Cache::remember(
                        Str::slug($eventTalksUrl),
                        now()->addDays(7),
                        function () use ($eventTalksUrl) {
                            $response = Http::accept('application/json')->get($eventTalksUrl);
                            if ($response->successful()) {
                                return $response->json();
                            } else {
                                throw new ConfreaksApiFailedException('Api call failed: '.$eventTalksUrl.' with status code:'.$response->status());
                            }
                        }
                    );

                    if (count($talks) === 0) {
                        $event->processed_at = null;
                        $event->save();
                    }

                    $this->withProgressBar(collect($talks), function ($talkData) {
                        $speakers = collect(Arr::get($talkData, 'presenters', []))
                            ->map(function ($presenterData) {
                                $presenter = ConfreaksSpeaker::firstOrNew($presenterData);

                                $data = collect($presenterData)
                                    ->filter(function ($v) {
                                        return ! empty($v);
                                    })->toArray();

                                $presenter->fill($data);
                                $presenter->save();

                                return $presenter->id;
                            });

                        $talkData = collect($talkData)
                            ->only(['id', 'title', 'event', 'abstract', 'host', 'embed_code', 'recorded_at'])
                            ->toArray();

                        $talk = ConfreaksTalk::firstOrNew($talkData);

                        $data = collect($talkData)
                            ->filter(function ($v) {
                                return ! empty($v);
                            })->toArray();

                        $talk->fill($data);
                        $talk->save();

                        $talk->speakers()->sync($speakers->toArray());
                    });

                    $event->processed_at = now();
                    $event->save();
                } catch (ConfreaksApiFailedException $ce) {
                    $event->processed_at = null;
                    $event->save();
                    Log::error('failed to import: '.$this->signature);
                    Log::error($ce->getMessage());
                }
            });

        return 0;
    }
}

<?php

namespace Domain\TalksAtConfs\Actions;

// use App\Jobs\ImportTalks;
use Domain\TalksAtConfs\Jobs\ImportTalks;
use Domain\TalksAtConfs\Models\Event;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ImportEvent
{
    public function __construct(protected $conference_id, protected $year, protected $filename)
    {
    }

    private function getPlaylistFromUrl($url): string|null
    {
        if (! empty($url)) {
            $urlData = parse_url((string) $url);
            $host = Str::after(Arr::get($urlData, 'host'), 'www.');
            $id = null;
            if (in_array($host, ['youtube.com', 'youtu.be'])) {
                parse_str((string) Arr::get($urlData, 'query'), $qParams);
                $id = Arr::get($qParams, 'list');
            } elseif (in_array($host, ['vimeo.com'])) {
                $id = collect(explode('/', (string) Arr::get($urlData, 'path')))->last();
            } else {
                $id = $url;
            }

            return $host . ':' . $id;
        }
    }

    public function handle(): void
    {
        $confData = (new Yaml())->parse(
            Storage::disk('confpad-data')
            ->get('/conferences/' . $this->year . '/' . $this->filename)
        );

        $event = Event::firstOrNew([
            'name' => Arr::get($confData, 'conference.name'),
            'conference_id' => $this->conference_id,
        ]);
        $eventAttrs = [
            'description' => Arr::get($confData, 'conference.description'),
            'from_date' => Arr::get($confData, 'conference.date.from'),
            'to_date' => Arr::get($confData, 'conference.date.to'),
            'city' => Arr::get($confData, 'conference.location.city'),
            'country' => Arr::get($confData, 'conference.location.country'),
            'link' => Arr::get($confData, 'conference.links.website'),
            'playlist' => $this->getPlaylistFromUrl(Arr::get($confData, 'conference.links.playlist')),
        ];
        $event->fill($eventAttrs);
        $event->save();

        ImportTalks::dispatch($event->id, $this->year, $this->filename);
    }
}

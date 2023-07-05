<?php

declare(strict_types=1);

namespace App\Sitemaps\Entities;

use App\Repositories\ConferenceRepository;
use App\Sitemaps\SitemapContract;
use Domain\TalksAtConfs\Models\Event;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class EventSitemap implements SitemapContract
{
    protected const SITEMAP_NAME = 'events_sitemap.xml';

    protected $sitemap;

    protected $events;

    protected $path;

    public function __construct()
    {
        // $this->events = new ConferenceRepository();
        $this->sitemap = Sitemap::create();

        $this->path = public_path(config('talksatconfs.sitemap_path').'/'.self::SITEMAP_NAME);
    }

    public function generate()
    {
        Event::chunk(10, function ($events) {
            foreach ($events as $event) {
                $this->sitemap->add(
                    Url::create(
                        $event->canonical_url
                    )->setLastModificationDate($event->updated_at)
                );
            }
        });

        $this->sitemap->writeToFile($this->path);

        return $this->path;
    }
}

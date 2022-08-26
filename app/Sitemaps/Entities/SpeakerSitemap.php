<?php

declare(strict_types=1);

namespace App\Sitemaps\Entities;

use App\Sitemaps\SitemapContract;
use Domain\TalksAtConfs\Models\Speaker;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SpeakerSitemap implements SitemapContract
{
    protected const SITEMAP_NAME = 'speakers_sitemap.xml';

    protected $sitemap;

    protected $speakers;

    protected $path;

    public function __construct()
    {
        $this->sitemap = Sitemap::create();

        $this->path = public_path(config('talksatconfs.sitemap_path') . '/' . self::SITEMAP_NAME);
    }

    public function generate()
    {
        Speaker::where('name', '<>', '')->chunk(10, function ($speakers) {
            foreach ($speakers as $speaker) {
                $this->sitemap->add(
                    Url::create(
                        $speaker->canonical_url
                    )->setLastModificationDate($speaker->updated_at)
                );
            }
        });

        $this->sitemap->writeToFile($this->path);

        return $this->path;
    }
}

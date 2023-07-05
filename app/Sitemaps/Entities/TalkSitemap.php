<?php

declare(strict_types=1);

namespace App\Sitemaps\Entities;

use App\Sitemaps\SitemapContract;
use Domain\TalksAtConfs\Models\Talk;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class TalkSitemap implements SitemapContract
{
    protected const SITEMAP_NAME = 'talks_sitemap.xml';

    protected $sitemap;

    protected $talks;

    protected $path;

    public function __construct()
    {
        $this->sitemap = Sitemap::create();

        $this->path = public_path(config('talksatconfs.sitemap_path').'/'.self::SITEMAP_NAME);
    }

    public function generate()
    {
        Talk::chunk(10, function ($talks) {
            foreach ($talks as $talk) {
                if (! is_null($talk->event)) {
                    $this->sitemap->add(
                        Url::create(
                            $talk->canonical_url
                        )->setLastModificationDate($talk->updated_at)
                    );
                }
            }
        });

        $this->sitemap->writeToFile($this->path);

        return $this->path;
    }
}

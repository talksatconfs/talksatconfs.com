<?php

declare(strict_types=1);

namespace App\Sitemaps\Entities;

use App\Sitemaps\SitemapContract;
use Domain\TalksAtConfs\Models\Conference;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class ConferenceSitemap implements SitemapContract
{
    protected const SITEMAP_NAME = 'conferences_sitemap.xml';

    protected $sitemap;

    protected $conferences;

    protected $path;

    public function __construct()
    {
        $this->sitemap = Sitemap::create();

        $this->path = public_path(config('talksatconfs.sitemap_path').'/'.self::SITEMAP_NAME);
    }

    public function generate()
    {
        Conference::chunk(10, function ($conferences) {
            foreach ($conferences as $conference) {
                $this->sitemap->add(
                    Url::create(
                        $conference->canonical_url
                    )->setLastModificationDate($conference->updated_at)
                );
            }
        });

        $this->sitemap->writeToFile($this->path);

        return $this->path;
    }
}

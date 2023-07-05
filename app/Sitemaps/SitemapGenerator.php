<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: swapnils
 * Date: 24/05/19
 * Time: 14:00.
 */

namespace App\Sitemaps;

use App\Sitemaps\Entities\ConferenceSitemap;
use App\Sitemaps\Entities\EventSitemap;
use App\Sitemaps\Entities\SpeakerSitemap;
use App\Sitemaps\Entities\TalkSitemap;
use Illuminate\Support\Arr;
use Spatie\Sitemap\SitemapIndex;

class SitemapGenerator
{
    protected $sitemapIndex;

    protected $generators = [
        'conferences' => ConferenceSitemap::class,
        'events' => EventSitemap::class,
        'speakers' => SpeakerSitemap::class,
        'talks' => TalkSitemap::class,

    ];

    public function __construct()
    {
        $this->sitemapIndex = SitemapIndex::create();
    }

    public function generate($entity = null): void
    {
        $entities = is_null($entity)
            ? $this->generators
            : [Arr::get($this->generators, $entity)];

        $sitemaps = collect($entities)->map(static function ($class) {
            return (new $class())->generate();
        });

        collect($sitemaps)->each(function ($sitemap) {
            $this->sitemapIndex->add(
                config('talksatconfs.domain').'/'.config('talksatconfs.sitemap_path').'/'.basename($sitemap)
            );
        });

        $this->sitemapIndex->writeToFile(public_path('/sitemap.xml'));
    }
}

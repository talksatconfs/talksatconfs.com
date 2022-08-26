<?php

declare(strict_types=1);

namespace App\Sitemaps;

interface SitemapContract
{
    /**
     * Gets the collection or array required for the Sitemap.
     * @return mixed
     */
    public function generate();
}

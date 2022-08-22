<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class RobotsController extends Controller
{
    public function __invoke()
    {
        return response('User-agent: Googlebot
Disallow: /nova/

User-agent: *
Allow: /

Sitemap: ' . config('talksatconfs.domain') . '/sitemaps/tac/sitemap.xml', 200, [
            'Content-type' => 'text',
        ]);
    }
}

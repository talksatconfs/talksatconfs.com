<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RobotsControllerTest extends TestCase
{
    #[Test]
    public function robots_txt_allows_crawlers_and_points_to_the_sitemap()
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('User-agent: *', false)
            ->assertSee('Allow: /', false)
            ->assertSee(config('talksatconfs.domain') . '/sitemap.xml', false);
    }
}

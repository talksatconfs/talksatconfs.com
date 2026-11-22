<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class HomepageControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_checks_if_homepage_works_fine()
    {
        $response = $this->get('/');
        $response->assertOk();
    }
}

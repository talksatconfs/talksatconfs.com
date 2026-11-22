<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class AboutUsControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_checks_if_aboutus_page_works_fine()
    {
        $response = $this->get('/about-us');
        $response->assertOk();
    }
}

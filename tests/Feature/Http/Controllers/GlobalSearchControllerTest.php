<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class GlobalSearchControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function search_page_loads_without_a_query()
    {
        $this->get('/search')->assertOk();
    }

    #[Test]
    public function search_page_loads_with_a_query()
    {
        $this->get('/search?query=laravel')->assertOk();
    }
}

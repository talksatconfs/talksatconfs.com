<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\TalksAtConfs\Http\Controllers;

use Domain\TalksAtConfs\Models\Talk;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class TalkRedirectControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    /** @test */
    public function it_checks_if_old_url_redirects_well_if_found_with_valid_slug()
    {
        $talk = Talk::factory()->create([
            'title' => 'A Wonderful Talk',
        ]);
        $response = $this->get('/talks/'.$talk->uuid.'/'.$talk->slug);

        $response->assertStatus(301);
    }

    /** @test */
    public function it_checks_if_old_url_redirects_well_if_invalid_slug_but_scout_search_found()
    {
        $talk = Talk::factory()->create([
            'title' => 'A Wonderful Talk',
        ]);
        $response = $this->get('/talks/'.$talk->uuid.'/wonderful');

        $response->assertStatus(301);
    }

    /** @test */
    public function it_checks_if_old_url_redirects_to_404_if_not_found()
    {
        $talk = Talk::factory()->create([
            'title' => 'A Wonderful Talk',
        ]);
        $response = $this->get('/talks/'.$talk->uuid.'/swapnilsarwe');

        $response->assertStatus(404);
    }
}

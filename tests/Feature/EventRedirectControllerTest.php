<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Domain\TalksAtConfs\Models\Event;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class EventRedirectControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    /** @test */
    public function it_checks_if_old_url_redirects_well_if_found_with_valid_slug()
    {
        $event = Event::factory()->create([
            'name' => 'Dummy Event',
        ]);
        $response = $this->get('/events/' . $event->uuid . '/' . $event->slug);

        $response->assertStatus(301);
    }

    /** @test */
    public function it_checks_if_old_url_redirects_well_if_invalid_slug_but_scout_search_found()
    {
        $event = Event::factory()->create([
            'name' => 'Dummy Event',
        ]);
        $response = $this->get('/events/' . $event->uuid . '/dummy');

        $response->assertStatus(301);
    }

    /** @test */
    public function it_checks_if_old_url_redirects_to_404_if_not_found()
    {
        $event = Event::factory()->create([
            'name' => 'Dummy Event',
        ]);
        $response = $this->get('/events/' . $event->uuid . '/swapnilsarwe');

        $response->assertStatus(404);
    }
}

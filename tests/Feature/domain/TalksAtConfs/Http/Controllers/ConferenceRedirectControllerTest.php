<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\TalksAtConfs\Http\Controllers;

use Domain\TalksAtConfs\Models\Conference;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class ConferenceRedirectControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    /** @test */
    public function it_checks_if_old_url_redirects_well_if_found_with_valid_slug()
    {
        $conference = Conference::factory()->create([
            'name' => 'John Doe',
        ]);
        $response = $this->get('/conferences/'.$conference->uuid.'/'.$conference->slug);

        $response->assertStatus(301);
    }

    /** @test */
    public function it_checks_if_old_url_redirects_well_if_invalid_slug_but_scout_search_found()
    {
        $conference = Conference::factory()->create([
            'name' => 'John Doe',
        ]);
        $response = $this->get('/conferences/'.$conference->uuid.'/john');

        $response->assertStatus(301);
    }

    /** @test */
    public function it_checks_if_old_url_redirects_to_404_if_not_found()
    {
        $conference = Conference::factory()->create([
            'name' => 'John Doe',
        ]);
        $response = $this->get('/conferences/'.$conference->uuid.'/swapnilsarwe');

        $response->assertStatus(404);
    }
}

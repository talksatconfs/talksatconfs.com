<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Domain\TalksAtConfs\Models\Talk;
use Domain\TalksAtConfs\Models\Conference;
use Tests\Traits\RecursiveRefreshDatabase;

class ConferenceTest extends TestCase
{
    use RecursiveRefreshDatabase;

    /** @test */
    public function it_checks_all_conference_attributes_are_clean()
    {
        $conference = Conference::factory()->create([
            'name' => 'Test Conference'
        ]);

        $this->assertEquals(
            'http://codeat3.test/conferences/test-conference',
            $conference->canonical_url
        );
    }
}

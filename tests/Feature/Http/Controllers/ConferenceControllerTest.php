<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Talk;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class ConferenceControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    /** @test */
    public function it_checks_if_listing_page_loads_when_no_records_exist()
    {
        $response = $this->get('/conferences');

        $response->assertSee(config('talksatconfs.conferences.messages.no_records'));

        $response->assertOk();
    }

    /** @test */
    public function it_checks_if_listing_page_loads_when_records_exists()
    {
        $conference = Conference::factory()
            ->has(
                Event::factory()
                ->has(Talk::factory())
            )
            ->create();

        $response = $this->get('/conferences');

        $response->assertSee($conference->title)
            ->assertOk();
    }

    /** @test */
    public function it_checks_if_listing_page_display_expected_data_in_correct_format()
    {
        $numberOfEvents = 1;
        $numberOfTalks = 3;
        $conference = Conference::factory()
            ->has(
                Event::factory()
                ->has(Talk::factory()->count($numberOfTalks))
                ->count($numberOfEvents)
            )
            ->create();

        $response = $this->get('/conferences');

        $response->assertSee($conference->title)
            ->assertSee($numberOfEvents . ' events')
            ->assertSee($numberOfTalks . ' talks')
            ->assertOk();
    }

    /** @test */
    public function it_checks_if_single_page_loads()
    {
        $conference = Conference::factory()->create();

        $response = $this->get('/conferences/' . $conference->slug);

        $response->assertSee($conference->name);
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_no_events()
    {
        $conference = Conference::factory()->create();

        $response = $this->get('/conferences/' . $conference->slug);

        $response->assertOk()
            ->assertSee($conference->name)
            ->assertSee(config('talksatconfs.events.messages.no_records'));
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_events()
    {
        $eventName = 'Dummy Event';
        $conference = Conference::factory()
            ->has(Event::factory()->state([
                'name' => $eventName,
            ]))
            ->create();

        $response = $this->get('/conferences/' . $conference->slug);

        $response->assertOk();
        $response->assertSee($conference->name);
        $response->assertSee($eventName);
        // $response->assertSee('Number of talks: 0');
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_events_and_talks()
    {
        $eventName = 'Dummy Event';
        $talkName = 'Dummy Talk';
        $conference = Conference::factory()
            ->has(
                Event::factory()
                ->has(
                    Talk::factory()
                        ->state([
                            'title' => $talkName,
                        ])
                )
                ->state([
                    'name' => $eventName,
                ])
            )
            ->create();

        $response = $this->get('/conferences/' . $conference->slug);
        $response->assertOk()
            ->assertSee($conference->name)
            ->assertSee($eventName)
            ->assertSee('1 talk');
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class EventControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    /** @test */
    public function it_checks_if_listing_page_loads_when_no_records_exist()
    {
        $response = $this->get('/events');

        $response->assertSee(config('talksatconfs.events.messages.no_records'));
        $response->assertOk();
    }

    /** @test */
    public function it_checks_if_listing_page_loads_when_records_exist()
    {
        Conference::factory()
            ->has(
                Event::factory()
                    ->state([
                        'from_date' => '2021-08-28',
                        'to_date' => '2021-09-10',
                    ])
                    ->has(Talk::factory())
            )
            ->create();

        $response = $this->get('/events');

        $response->assertOk();
    }

    /** @test */
    public function it_checks_if_listing_page_display_expected_data_in_correct_format()
    {
        $number_of_events = 1;
        $number_of_talks = 2;
        Conference::factory()
            ->has(
                Event::factory()
                    ->state([
                        'from_date' => '2021-08-28',
                        'to_date' => '2021-09-10',
                    ])
                    ->has(Talk::factory()->count($number_of_talks))
                    ->count($number_of_events)
            )
            ->create();

        $response = $this->get('/events');

        $response->assertSee($number_of_talks . ' talks')
            ->assertSee('28 Aug, 2021')
            ->assertSee('10 Sep, 2021')
            ->assertOk();
    }

    /** @test */
    public function it_checks_if_single_page_loads()
    {
        $event = Event::factory()->create();

        $response = $this->get('/events/' . $event->slug);

        $response->assertSee($event->name);
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_no_talks()
    {
        $event = Event::factory()->create();

        $response = $this->get('/events/' . $event->slug);

        $response->assertOk();
        $response->assertSee($event->name);
        $response->assertSee(config('talksatconfs.talks.messages.no_records'));
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_talks()
    {
        $eventName = 'Dummy Event';
        $talkTitle = 'Dummy Talk Title';
        $event = Event::factory()
            ->has(
                Talk::factory()
                    ->state([
                        'title' => $talkTitle,
                    ])
            )
            ->state([
                'name' => $eventName,
            ])
            ->create();

        $response = $this->get('/events/' . $event->slug);

        $response->assertOk();
        $response->assertSee($eventName);
        $response->assertSee($talkTitle);
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_talks_and_speakers()
    {
        $eventName = 'Dummy Event';
        $talkName = 'Dummy Talk';
        $speakerName = 'John Doe';
        $speaker = Speaker::factory()->create([
            'name' => $speakerName,
        ]);
        $event = Event::factory()
            ->has(
                Talk::factory()
                    ->hasAttached($speaker)
                    ->state([
                        'title' => $talkName,
                    ])
            )
            ->create([
                'name' => $eventName,
            ]);

        $response = $this->get('/events/' . $event->slug);
        $response->assertOk();
        $response->assertSee($eventName);
        $response->assertSee($talkName);
        $response->assertSee($speakerName);
    }
}

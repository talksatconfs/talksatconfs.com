<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use Domain\TalksAtConfs\Models\Video;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class TalkControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    /** @test */
    public function it_checks_if_listing_page_loads_when_no_records_exist()
    {
        $response = $this->get('/talks');

        $response->assertSee(config('talksatconfs.talks.messages.no_records'));
        $response->assertOk();
    }

    /** @test */
    public function it_checks_if_listing_page_loads_when_records_exist()
    {
        $event = Event::factory()->create();
        $speaker = Speaker::factory()->create();
        Talk::factory()
            ->for($event)
            ->hasAttached($speaker)
            ->create();

        $response = $this->get('/talks');

        $response->assertOk();
    }

    /** @test */
    public function it_checks_if_listing_page_display_expected_data_in_correct_format()
    {
        $event = Event::factory()->create();
        $speaker = Speaker::factory()->create();
        $video = Video::factory()->create([
            'source' => 'youtube',
        ]);
        $talk = Talk::factory()
            ->for($event)
            ->hasAttached($speaker)
            ->hasAttached($video)
            ->create();

        $video2 = Video::factory()->create([
            'source' => 'vimeo',
        ]);
        $talk2 = Talk::factory()
            ->for($event)
            ->hasAttached($speaker)
            ->hasAttached($video2)
            ->create();

        $response = $this->get('/talks');

        $response->assertOk();
        $response->assertSee($event->title);

        $response->assertSee($speaker->name);
        $response->assertSee($talk->title);
        $response->assertSee($video->video_embed_link);

        $response->assertSee($talk2->title);
        $response->assertSee($video2->video_embed_link);
    }

    /** @test */
    public function it_checks_if_listing_page_display_expected_data_when_multiple_speakers()
    {
        $event = Event::factory()->create();
        $speakers = Speaker::factory()->count(2)->create();

        $talk = Talk::factory()
            ->for($event)
            ->hasAttached($speakers)
            ->create();

        $response = $this->get('/talks');

        $response->assertOk();
        $response->assertSee($talk->title);
        $response->assertSee($event->title);

        $speakers->each(function ($speaker) use ($response) {
            $response->assertSee($speaker->name);
        });
    }

    /** @test */
    public function it_checks_if_single_page_loads()
    {
        $talk = Talk::factory()->create();

        $response = $this->get('/events/' . $talk->event->slug . '/talks/' . $talk->slug);

        $response->assertOk();
        $response->assertSee($talk->title);
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_no_speakers()
    {
        $talk = Talk::factory()->create();

        $response = $this->get('/events/' . $talk->event->slug . '/talks/' . $talk->slug);

        $response->assertOk();
    }

    /** @test */
    public function it_checks_if_single_page_loads_with_speakers()
    {
        $talkTitle = 'Dummy Talk Title';
        $speakerName = 'John Doe';
        $speaker = Speaker::factory()->create([
            'name' => $speakerName,
        ]);
        $talk = Talk::factory()
            ->hasAttached($speaker)
            ->state([
                'title' => $talkTitle,
            ])
            ->create();

        $response = $this->get('/events/' . $talk->event->slug . '/talks/' . $talk->slug);

        $response->assertOk();
        $response->assertSee($talkTitle);
        $response->assertSee($speakerName);
    }
}

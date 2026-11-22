<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class SpeakerControllerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function listing_page_loads_when_no_speakers_exist()
    {
        $this->get('/speakers')->assertOk();
    }

    #[Test]
    public function listing_page_shows_existing_speakers()
    {
        $speaker = Speaker::factory()->create(['name' => 'Ada Lovelace']);

        $this->get('/speakers')
            ->assertOk()
            ->assertSee('Ada Lovelace');
    }

    #[Test]
    public function speaker_page_shows_name_and_their_talks()
    {
        $speaker = Speaker::factory()->create(['name' => 'Grace Hopper']);
        $talk = Talk::factory()
            ->for(Event::factory())
            ->create(['title' => 'Nanoseconds Explained']);
        $speaker->talks()->attach($talk);

        $this->get('/speakers/' . $speaker->slug)
            ->assertOk()
            ->assertSee('Grace Hopper')
            ->assertSee('Nanoseconds Explained');
    }

    #[Test]
    public function unknown_speaker_returns_404()
    {
        $this->get('/speakers/does-not-exist')->assertNotFound();
    }
}

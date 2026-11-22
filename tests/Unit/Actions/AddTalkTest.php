<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use Domain\TalksAtConfs\Actions\AddTalk;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Talk;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class AddTalkTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_creates_a_talk_for_an_event()
    {
        $event = Event::factory()->create();

        $talk = (new AddTalk())->handle([
            'event_id' => $event->id,
            'title' => 'A Great Talk',
            'description' => 'About things',
        ]);

        $this->assertTrue($talk->exists);
        $this->assertSame('a-great-talk', $talk->slug);
        $this->assertSame($event->id, $talk->event_id);
    }

    #[Test]
    public function it_does_not_duplicate_a_talk_within_the_same_event()
    {
        $event = Event::factory()->create();
        $data = ['event_id' => $event->id, 'title' => 'A Great Talk'];

        (new AddTalk())->handle($data);
        (new AddTalk())->handle($data + ['description' => 'now with description']);

        $this->assertSame(1, Talk::count());
        $this->assertSame('now with description', Talk::first()->description);
    }

    #[Test]
    public function the_same_title_in_different_events_creates_separate_talks()
    {
        $title = 'A Great Talk';

        (new AddTalk())->handle(['event_id' => Event::factory()->create()->id, 'title' => $title]);
        (new AddTalk())->handle(['event_id' => Event::factory()->create()->id, 'title' => $title]);

        $this->assertSame(2, Talk::count());
    }
}

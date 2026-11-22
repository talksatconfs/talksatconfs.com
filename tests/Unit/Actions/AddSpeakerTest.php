<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use Domain\TalksAtConfs\Actions\AddSpeaker;
use Domain\TalksAtConfs\Models\Speaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class AddSpeakerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_creates_a_speaker()
    {
        $speaker = (new AddSpeaker())->handle(['name' => 'Ada Lovelace', 'github' => 'ada']);

        $this->assertTrue($speaker->exists);
        $this->assertSame('ada-lovelace', $speaker->slug);
        $this->assertSame('ada', $speaker->github);
        $this->assertNotEmpty($speaker->uuid);
    }

    #[Test]
    public function it_is_idempotent_on_the_slug()
    {
        (new AddSpeaker())->handle(['name' => 'Ada Lovelace']);
        (new AddSpeaker())->handle(['name' => 'Ada Lovelace']);

        $this->assertSame(1, Speaker::count());
    }

    #[Test]
    public function it_updates_existing_speaker_but_ignores_empty_values()
    {
        (new AddSpeaker())->handle(['name' => 'Ada Lovelace', 'github' => 'ada']);

        $speaker = (new AddSpeaker())->handle([
            'name' => 'Ada Lovelace',
            'github' => '',
            'twitter' => 'ada_tw',
        ]);

        $this->assertSame('ada', $speaker->fresh()->github);
        $this->assertSame('ada_tw', $speaker->fresh()->twitter);
    }
}

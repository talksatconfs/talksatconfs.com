<?php

declare(strict_types=1);

namespace Tests\Unit;

use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class TalkSpeakersTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function placeholder_speakers_are_not_linked_from_a_talk()
    {
        $talk = Talk::factory()->create();
        $real = Speaker::factory()->create(['name' => 'Ada Lovelace']);
        $placeholder = Speaker::factory()->create(['name' => '']);
        $talk->speakers()->attach([$real->id, $placeholder->id]);

        $this->assertSame(['Ada Lovelace'], $talk->fresh()->speakers->pluck('name')->all());
        $this->assertSame('Ada Lovelace', $talk->fresh()->speakers_names);
        $this->assertStringNotContainsString('speakers//', $talk->fresh()->speakers_links);
    }

    #[Test]
    public function listed_scope_excludes_speakers_without_a_name_or_slug()
    {
        Speaker::factory()->create(['name' => 'Ada Lovelace']);
        Speaker::factory()->create(['name' => '']);

        $this->assertSame(1, Speaker::listed()->count());
    }
}

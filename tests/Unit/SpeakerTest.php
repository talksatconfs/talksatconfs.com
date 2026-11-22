<?php

declare(strict_types=1);

namespace Tests\Unit;

use Domain\TalksAtConfs\Models\Speaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class SpeakerTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function setting_the_name_generates_the_slug()
    {
        $speaker = Speaker::factory()->create(['name' => 'Taylor Otwell']);

        $this->assertSame('taylor-otwell', $speaker->slug);
    }

    #[Test]
    public function it_builds_social_links()
    {
        $speaker = Speaker::factory()->make([
            'github' => 'octocat',
            'twitter' => 'jack',
            'youtube' => '@chan',
        ]);

        $this->assertSame('https://github.com/octocat', $speaker->github_link);
        $this->assertSame('https://twitter.com/jack', $speaker->twitter_link);
        $this->assertSame('https://youtube.com/@chan', $speaker->youtube_link);
    }

    #[Test]
    public function canonical_url_uses_the_slug()
    {
        $speaker = Speaker::factory()->create(['name' => 'Taylor Otwell']);

        $this->assertSame(route('speakers.show', ['speaker' => 'taylor-otwell']), $speaker->canonical_url);
    }

    #[Test]
    public function details_scope_counts_talks()
    {
        $speaker = Speaker::factory()->create();

        $this->assertSame(0, Speaker::details()->find($speaker->id)->talks_count);
    }

    #[Test]
    public function searchable_array_contains_expected_keys()
    {
        $speaker = Speaker::factory()->create();

        $this->assertSame(
            ['id', 'uuid', 'name', 'bio', 'website', 'twitter', 'github'],
            array_keys($speaker->toSearchableArray()),
        );
    }
}

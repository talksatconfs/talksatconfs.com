<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\LatestTalks;
use App\Livewire\RecentlyAddedTalks;
use Domain\TalksAtConfs\Models\Talk;
use Domain\TalksAtConfs\Models\Video;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class HomepageTalksWidgetsTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function latest_talks_shows_six_newest_by_talk_date()
    {
        foreach (range(1, 7) as $i) {
            Talk::factory()->create(['title' => "Talk number $i", 'talk_date' => "2024-01-0$i"]);
        }

        $talks = Livewire::test(LatestTalks::class)->viewData('talks');

        $this->assertCount(6, $talks);
        $this->assertSame('Talk number 7', $talks->first()->title);
        $this->assertNotContains('Talk number 1', $talks->pluck('title'));
    }

    #[Test]
    public function latest_talks_can_be_limited_to_talks_with_video()
    {
        Talk::factory()->create(['title' => 'Recorded Talk'])
            ->videos()->attach(Video::factory()->create());
        Talk::factory()->create(['title' => 'Unrecorded Talk']);

        Livewire::test(LatestTalks::class, ['latestWithVideo' => true])
            ->assertSee('Recorded Talk')
            ->assertDontSee('Unrecorded Talk');
    }

    #[Test]
    public function recently_added_talks_shows_six_newest_by_created_date()
    {
        foreach (range(1, 7) as $i) {
            Talk::factory()->create([
                'title' => "Added number $i",
                'created_at' => now()->subDays(10 - $i),
            ]);
        }

        $talks = Livewire::test(RecentlyAddedTalks::class)->viewData('talks');

        $this->assertCount(6, $talks);
        $this->assertSame('Added number 7', $talks->first()->title);
        $this->assertNotContains('Added number 1', $talks->pluck('title'));
    }

    #[Test]
    public function recently_added_talks_can_be_limited_to_talks_with_video()
    {
        Talk::factory()->create(['title' => 'Recorded Talk'])
            ->videos()->attach(Video::factory()->create());
        Talk::factory()->create(['title' => 'Unrecorded Talk']);

        Livewire::test(RecentlyAddedTalks::class, ['recentWithVideo' => true])
            ->assertSee('Recorded Talk')
            ->assertDontSee('Unrecorded Talk');
    }
}

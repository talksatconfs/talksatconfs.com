<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\TalkListing;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Speaker;
use Domain\TalksAtConfs\Models\Talk;
use Domain\TalksAtConfs\Models\Video;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class TalkListingTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_lists_talks()
    {
        Talk::factory()->create(['title' => 'Scaling Queues']);

        Livewire::test(TalkListing::class)->assertSee('Scaling Queues');
    }

    #[Test]
    public function it_filters_by_search_query()
    {
        Talk::factory()->create(['title' => 'Scaling Queues']);
        Talk::factory()->create(['title' => 'Testing Livewire']);

        Livewire::test(TalkListing::class, ['query' => 'Livewire'])
            ->assertSee('Testing Livewire')
            ->assertDontSee('Scaling Queues');
    }

    #[Test]
    public function it_only_lists_talks_of_the_given_event()
    {
        $event = Event::factory()->create();
        Talk::factory()->for($event)->create(['title' => 'Inside Talk']);
        Talk::factory()->create(['title' => 'Outside Talk']);

        Livewire::test(TalkListing::class, ['event' => $event])
            ->assertSee('Inside Talk')
            ->assertDontSee('Outside Talk');
    }

    #[Test]
    public function it_only_lists_talks_of_the_given_speaker()
    {
        $speaker = Speaker::factory()->create();
        $mine = Talk::factory()->create(['title' => 'Speaker Talk']);
        $mine->speakers()->attach($speaker);
        Talk::factory()->create(['title' => 'Someone Elses Talk']);

        Livewire::test(TalkListing::class, ['speaker' => $speaker])
            ->assertSee('Speaker Talk')
            ->assertDontSee('Someone Elses Talk');
    }

    #[Test]
    public function with_video_only_lists_talks_that_have_videos()
    {
        $withVideo = Talk::factory()->create(['title' => 'Recorded Talk']);
        $withVideo->videos()->attach(Video::factory()->create());
        Talk::factory()->create(['title' => 'Unrecorded Talk']);

        Livewire::test(TalkListing::class, ['withVideo' => true])
            ->assertSee('Recorded Talk')
            ->assertDontSee('Unrecorded Talk');
    }

    #[Test]
    public function it_orders_by_talk_date_descending()
    {
        Talk::factory()->create(['title' => 'Older Talk', 'talk_date' => '2020-01-01']);
        Talk::factory()->create(['title' => 'Newer Talk', 'talk_date' => '2024-01-01']);

        Livewire::test(TalkListing::class)->assertSeeInOrder(['Newer Talk', 'Older Talk']);
    }

    #[Test]
    public function it_paginates_twelve_per_page()
    {
        Talk::factory()->count(13)->create();

        $talks = Livewire::test(TalkListing::class)->viewData('talks');

        $this->assertCount(12, $talks->items());
        $this->assertSame(13, $talks->total());
    }
}

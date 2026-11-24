<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\SpeakerListing;
use Domain\TalksAtConfs\Models\Speaker;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class SpeakerListingTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_lists_speakers()
    {
        Speaker::factory()->create(['name' => 'Ada Lovelace']);

        Livewire::test(SpeakerListing::class)->assertSee('Ada Lovelace');
    }

    #[Test]
    public function it_filters_by_search_query()
    {
        Speaker::factory()->create(['name' => 'Ada Lovelace']);
        Speaker::factory()->create(['name' => 'Alan Turing']);

        Livewire::test(SpeakerListing::class, ['query' => 'Turing'])
            ->assertSee('Alan Turing')
            ->assertDontSee('Ada Lovelace');
    }

    #[Test]
    public function it_paginates_twelve_per_page()
    {
        Speaker::factory()->count(13)->create();

        $speakers = Livewire::test(SpeakerListing::class)->viewData('speakers');

        $this->assertCount(12, $speakers->items());
        $this->assertSame(13, $speakers->total());
    }

    #[Test]
    public function it_skips_placeholder_speakers_without_a_name_or_slug()
    {
        Speaker::factory()->create(['name' => 'Ada Lovelace']);
        $placeholder = Speaker::factory()->create(['name' => '']);

        $this->assertSame('', $placeholder->slug);

        Livewire::test(SpeakerListing::class)
            ->assertOk()
            ->assertSee('Ada Lovelace')
            ->assertViewHas('speakers', fn ($speakers) => $speakers->total() === 1);
    }

    #[Test]
    public function search_results_skip_placeholder_speakers()
    {
        Speaker::factory()->create(['name' => 'Ada Lovelace']);
        Speaker::factory()->create(['name' => '']);

        Livewire::test(SpeakerListing::class, ['query' => 'Ada'])
            ->assertOk()
            ->assertSee('Ada Lovelace')
            ->assertViewHas('speakers', fn ($speakers) => $speakers->total() === 1);
    }
}

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
}

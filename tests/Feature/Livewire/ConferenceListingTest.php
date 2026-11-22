<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ConferenceListing;
use Domain\TalksAtConfs\Models\Conference;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class ConferenceListingTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_lists_conferences()
    {
        Conference::factory()->create(['name' => 'LaraCon']);

        Livewire::test(ConferenceListing::class)->assertSee('LaraCon');
    }

    #[Test]
    public function it_filters_by_search_query()
    {
        Conference::factory()->create(['name' => 'LaraCon']);
        Conference::factory()->create(['name' => 'PyCon']);

        Livewire::test(ConferenceListing::class, ['query' => 'PyCon'])
            ->assertSee('PyCon')
            ->assertDontSee('LaraCon');
    }

    #[Test]
    public function it_paginates_ten_per_page()
    {
        Conference::factory()->count(11)->create();

        $conferences = Livewire::test(ConferenceListing::class)->viewData('conferences');

        $this->assertCount(10, $conferences->items());
        $this->assertSame(11, $conferences->total());
    }
}

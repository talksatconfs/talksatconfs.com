<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\EventListing;
use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class EventListingTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function it_lists_events()
    {
        Event::factory()->create(['name' => 'LaraCon 2024']);

        Livewire::test(EventListing::class)->assertSee('LaraCon 2024');
    }

    #[Test]
    public function it_filters_by_search_query()
    {
        Event::factory()->create(['name' => 'LaraCon 2024']);
        Event::factory()->create(['name' => 'PyCon 2024']);

        Livewire::test(EventListing::class, ['query' => 'PyCon'])
            ->assertSee('PyCon 2024')
            ->assertDontSee('LaraCon 2024');
    }

    #[Test]
    public function it_only_lists_events_of_the_given_conference()
    {
        $conference = Conference::factory()->create();
        Event::factory()->for($conference)->create(['name' => 'Inside Event']);
        Event::factory()->create(['name' => 'Outside Event']);

        Livewire::test(EventListing::class, ['conference' => $conference])
            ->assertSee('Inside Event')
            ->assertDontSee('Outside Event');
    }

    #[Test]
    public function it_scopes_search_results_to_the_given_conference()
    {
        $conference = Conference::factory()->create();
        Event::factory()->for($conference)->create(['name' => 'Summit Inside']);
        Event::factory()->create(['name' => 'Summit Outside']);

        Livewire::test(EventListing::class, ['conference' => $conference, 'query' => 'Summit'])
            ->assertSee('Summit Inside')
            ->assertDontSee('Summit Outside');
    }

    #[Test]
    public function it_paginates_ten_per_page()
    {
        Event::factory()->count(11)->create();

        $events = Livewire::test(EventListing::class)->viewData('events');

        $this->assertCount(10, $events->items());
        $this->assertSame(11, $events->total());
    }
}

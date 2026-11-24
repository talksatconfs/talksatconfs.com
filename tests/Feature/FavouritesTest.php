<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\FavouriteButton;
use App\Models\Favourite;
use App\Models\User;
use Domain\TalksAtConfs\Models\Conference;
use Domain\TalksAtConfs\Models\Event;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class FavouritesTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function guests_are_sent_to_login_when_favouriting()
    {
        $talk = Talk::factory()->create();

        Livewire::test(FavouriteButton::class, ['type' => 'talk', 'id' => $talk->id])
            ->call('toggle')
            ->assertRedirect(route('login'));

        $this->assertSame(0, Favourite::count());
    }

    #[Test]
    public function unverified_users_are_sent_to_verification_when_favouriting()
    {
        $user = User::factory()->unverified()->create();
        $talk = Talk::factory()->create();

        Livewire::actingAs($user)
            ->test(FavouriteButton::class, ['type' => 'talk', 'id' => $talk->id])
            ->call('toggle')
            ->assertRedirect(route('verification.notice'));

        $this->assertSame(0, Favourite::count());
    }

    public static function entities(): array
    {
        return [
            'talk' => ['talk', Talk::class],
            'event' => ['event', Event::class],
            'conference' => ['conference', Conference::class],
        ];
    }

    #[Test]
    #[DataProvider('entities')]
    public function a_verified_user_can_favourite_and_unfavourite(string $type, string $model)
    {
        $user = User::factory()->create();
        $entity = $model::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(FavouriteButton::class, ['type' => $type, 'id' => $entity->id]);

        $component->assertSet('favourited', false)
            ->call('toggle')
            ->assertSet('favourited', true);

        $this->assertDatabaseHas('favourites', [
            'user_id' => $user->id,
            'favouritable_type' => $type,
            'favouritable_id' => $entity->id,
        ]);
        $this->assertTrue($user->hasFavourited($entity));

        $component->call('toggle')->assertSet('favourited', false);
        $this->assertSame(0, Favourite::count());
    }

    #[Test]
    public function the_button_starts_in_the_favourited_state()
    {
        $user = User::factory()->create();
        $talk = Talk::factory()->create();
        $user->toggleFavourite($talk);

        Livewire::actingAs($user)
            ->test(FavouriteButton::class, ['type' => 'talk', 'id' => $talk->id])
            ->assertSet('favourited', true)
            ->assertSee('Favourited');
    }

    #[Test]
    public function an_unknown_entity_type_is_rejected()
    {
        Livewire::test(FavouriteButton::class, ['type' => 'speaker', 'id' => 1])
            ->assertNotFound();
    }

    #[Test]
    public function favouriting_is_rate_limited()
    {
        $user = User::factory()->create();
        $talk = Talk::factory()->create();

        for ($i = 0; $i < 30; $i++) {
            RateLimiter::hit('favourite:' . $user->id, 60);
        }

        Livewire::actingAs($user)
            ->test(FavouriteButton::class, ['type' => 'talk', 'id' => $talk->id])
            ->call('toggle')
            ->assertSet('favourited', false)
            ->assertSee('Slow down');

        $this->assertSame(0, Favourite::count());
    }

    #[Test]
    public function favourites_belong_to_their_user_only()
    {
        $talk = Talk::factory()->create();
        $a = User::factory()->create();
        $b = User::factory()->create();

        $a->toggleFavourite($talk);

        $this->assertTrue($a->hasFavourited($talk));
        $this->assertFalse($b->hasFavourited($talk));
    }

    #[Test]
    public function favourites_page_requires_a_verified_login()
    {
        $this->get('/favourites')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->unverified()->create())
            ->get('/favourites')
            ->assertRedirect(route('verification.notice'));
    }

    #[Test]
    public function favourites_page_lists_only_my_items()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $mine = Talk::factory()->create(['title' => 'My Favourite Talk']);
        $event = Event::factory()->create(['name' => 'My Favourite Event']);
        $conference = Conference::factory()->create(['name' => 'My Favourite Conf']);
        $theirs = Talk::factory()->create(['title' => 'Somebody Elses Talk']);

        $user->toggleFavourite($mine);
        $user->toggleFavourite($event);
        $user->toggleFavourite($conference);
        $other->toggleFavourite($theirs);

        $this->actingAs($user)->get('/favourites')
            ->assertOk()
            ->assertSee('My Favourite Talk')
            ->assertSee('My Favourite Event')
            ->assertSee('My Favourite Conf')
            ->assertDontSee('Somebody Elses Talk');
    }

    #[Test]
    public function favourites_page_has_an_empty_state()
    {
        $this->actingAs(User::factory()->create())
            ->get('/favourites')
            ->assertOk()
            ->assertSee('You have not favourited anything yet');
    }

    #[Test]
    public function favourites_and_ratings_are_deleted_with_their_user()
    {
        foreach (['favourites', 'talk_ratings'] as $table) {
            $foreignKey = collect(Schema::getForeignKeys($table))
                ->first(fn ($key) => $key['columns'] === ['user_id']);

            $this->assertNotNull($foreignKey, "{$table} has no foreign key on user_id");
            $this->assertSame('cascade', $foreignKey['on_delete']);
        }
    }

    #[Test]
    public function detail_pages_show_the_favourite_button_and_the_header_has_auth_links()
    {
        $talk = Talk::factory()->create();

        $this->get($talk->canonical_url)
            ->assertOk()
            ->assertSee('Favourite')
            ->assertSee('Log in')
            ->assertSee('Sign up');

        $this->actingAs(User::factory()->create())
            ->get($talk->event->canonical_url)
            ->assertOk()
            ->assertSee('Favourite')
            ->assertSee('Log out');
    }
}

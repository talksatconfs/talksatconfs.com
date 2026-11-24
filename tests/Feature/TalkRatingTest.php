<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\TalkRating;
use App\Models\TalkRating as Rating;
use App\Models\User;
use Domain\TalksAtConfs\Models\Talk;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\RecursiveRefreshDatabase;

class TalkRatingTest extends TestCase
{
    use RecursiveRefreshDatabase;

    #[Test]
    public function guests_can_see_the_average_but_are_sent_to_login_to_rate()
    {
        $talk = Talk::factory()->create();
        Rating::create(['user_id' => User::factory()->create()->id, 'talk_id' => $talk->id, 'rating' => 4]);

        Livewire::test(TalkRating::class, ['talkId' => $talk->id])
            ->assertSee('4.0')
            ->assertSee('1 rating')
            ->call('rate', 5)
            ->assertRedirect(route('login'));

        $this->assertSame(1, Rating::count());
    }

    #[Test]
    public function unverified_users_are_sent_to_verification_to_rate()
    {
        $talk = Talk::factory()->create();

        Livewire::actingAs(User::factory()->unverified()->create())
            ->test(TalkRating::class, ['talkId' => $talk->id])
            ->call('rate', 5)
            ->assertRedirect(route('verification.notice'));

        $this->assertSame(0, Rating::count());
    }

    #[Test]
    public function a_verified_user_can_rate_and_change_their_rating()
    {
        $user = User::factory()->create();
        $talk = Talk::factory()->create();

        $component = Livewire::actingAs($user)->test(TalkRating::class, ['talkId' => $talk->id]);

        $component->call('rate', 3)->assertSet('myRating', 3);
        $component->call('rate', 5)->assertSet('myRating', 5);

        $this->assertSame(1, Rating::count());
        $this->assertDatabaseHas('talk_ratings', ['user_id' => $user->id, 'talk_id' => $talk->id, 'rating' => 5]);
    }

    #[Test]
    public function ratings_outside_one_to_five_are_ignored()
    {
        $talk = Talk::factory()->create();

        $component = Livewire::actingAs(User::factory()->create())
            ->test(TalkRating::class, ['talkId' => $talk->id]);

        foreach ([0, 6, -1, 100] as $invalid) {
            $component->call('rate', $invalid)->assertSet('myRating', null);
        }

        $this->assertSame(0, Rating::count());
    }

    #[Test]
    public function the_average_and_count_cover_all_users()
    {
        $talk = Talk::factory()->create();
        foreach ([5, 4, 4] as $value) {
            Rating::create(['user_id' => User::factory()->create()->id, 'talk_id' => $talk->id, 'rating' => $value]);
        }

        $this->assertSame(['average' => 4.3, 'count' => 3], Rating::summaryFor($talk->id));
        $this->assertSame(['average' => null, 'count' => 0], Rating::summaryFor(Talk::factory()->create()->id));
    }

    #[Test]
    public function a_user_can_clear_their_rating()
    {
        $user = User::factory()->create();
        $talk = Talk::factory()->create();
        Rating::create(['user_id' => $user->id, 'talk_id' => $talk->id, 'rating' => 2]);

        Livewire::actingAs($user)
            ->test(TalkRating::class, ['talkId' => $talk->id])
            ->assertSet('myRating', 2)
            ->call('clear')
            ->assertSet('myRating', null);

        $this->assertSame(0, Rating::count());
    }

    #[Test]
    public function the_database_allows_one_rating_per_user_per_talk()
    {
        $user = User::factory()->create();
        $talk = Talk::factory()->create();
        Rating::create(['user_id' => $user->id, 'talk_id' => $talk->id, 'rating' => 2]);

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        Rating::create(['user_id' => $user->id, 'talk_id' => $talk->id, 'rating' => 3]);
    }

    #[Test]
    public function the_talk_page_shows_the_rating_widget()
    {
        $talk = Talk::factory()->create();

        $this->get($talk->canonical_url)
            ->assertOk()
            ->assertSee('No ratings yet')
            ->assertSee('to rate');
    }
}

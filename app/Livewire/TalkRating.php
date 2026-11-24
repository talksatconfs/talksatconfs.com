<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\TalkRating as Rating;
use Domain\TalksAtConfs\Models\Talk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class TalkRating extends Component
{
    public int $talkId;

    public ?int $myRating = null;

    public ?string $message = null;

    public function mount(int $talkId): void
    {
        $this->talkId = $talkId;

        $user = Auth::user();
        if ($user !== null && $user->hasVerifiedEmail()) {
            $this->myRating = Rating::query()
                ->where('user_id', $user->getKey())
                ->where('talk_id', $talkId)
                ->value('rating');
        }
    }

    public function rate(int $rating)
    {
        $user = Auth::user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if ($rating < Rating::MIN || $rating > Rating::MAX) {
            return;
        }

        if (! RateLimiter::attempt('rate:' . $user->getKey(), 30, fn () => true)) {
            $this->message = 'Slow down a little and try again in a minute.';

            return;
        }

        $this->message = null;
        Talk::findOrFail($this->talkId);

        Rating::updateOrCreate(
            ['user_id' => $user->getKey(), 'talk_id' => $this->talkId],
            ['rating' => $rating],
        );

        $this->myRating = $rating;
    }

    public function clear(): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        Rating::query()
            ->where('user_id', $user->getKey())
            ->where('talk_id', $this->talkId)
            ->delete();

        $this->myRating = null;
    }

    public function render()
    {
        return view('livewire.talk-rating', [
            'summary' => Rating::summaryFor($this->talkId),
        ]);
    }
}

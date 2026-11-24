<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Favourite;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class FavouriteButton extends Component
{
    public string $type;

    public int $id;

    public bool $favourited = false;

    public ?string $message = null;

    public function mount(string $type, int $id): void
    {
        abort_unless(in_array($type, Favourite::TYPES, true), 404);

        $this->type = $type;
        $this->id = $id;

        $user = Auth::user();
        $this->favourited = $user !== null
            && $user->hasVerifiedEmail()
            && $user->favourites()
                ->where('favouritable_type', $type)
                ->where('favouritable_id', $id)
                ->exists();
    }

    public function toggle()
    {
        $user = Auth::user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if (! RateLimiter::attempt('favourite:' . $user->getKey(), 30, fn () => true)) {
            $this->message = 'Slow down a little and try again in a minute.';

            return;
        }

        $this->message = null;
        $model = $this->resolveModel();
        $this->favourited = $user->toggleFavourite($model);
    }

    private function resolveModel()
    {
        $class = Relation::getMorphedModel($this->type);

        return $class::findOrFail($this->id);
    }

    public function render()
    {
        return view('livewire.favourite-button');
    }
}

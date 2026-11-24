<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class);
    }

    public function talkRatings(): HasMany
    {
        return $this->hasMany(TalkRating::class);
    }

    public function hasFavourited(Model $model): bool
    {
        return $this->favourites()
            ->where('favouritable_type', $model->getMorphClass())
            ->where('favouritable_id', $model->getKey())
            ->exists();
    }

    /**
     * Adds or removes the favourite; returns true when the item is now a favourite.
     */
    public function toggleFavourite(Model $model): bool
    {
        $attributes = [
            'favouritable_type' => $model->getMorphClass(),
            'favouritable_id' => $model->getKey(),
        ];

        $existing = $this->favourites()->where($attributes)->first();

        if ($existing) {
            $existing->delete();

            return false;
        }

        $this->favourites()->create($attributes);

        return true;
    }
}

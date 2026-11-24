<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favourite extends Model
{
    /** Entities that can be favourited (keys of the morph map). */
    public const TYPES = ['talk', 'event', 'conference'];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favouritable(): MorphTo
    {
        return $this->morphTo();
    }
}

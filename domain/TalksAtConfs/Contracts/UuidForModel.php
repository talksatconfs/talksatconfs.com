<?php

declare(strict_types=1);

namespace Domain\TalksAtConfs\Contracts;

use Illuminate\Support\Str;

trait UuidForModel
{
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}

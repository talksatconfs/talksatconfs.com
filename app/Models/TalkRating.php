<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalkRating extends Model
{
    public const MIN = 1;

    public const MAX = 5;

    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array{average: float|null, count: int}
     */
    public static function summaryFor(int $talkId): array
    {
        $row = static::query()
            ->where('talk_id', $talkId)
            ->selectRaw('avg(rating) as average, count(*) as total')
            ->first();

        return [
            'average' => $row->total > 0 ? round((float) $row->average, 1) : null,
            'count' => (int) $row->total,
        ];
    }
}

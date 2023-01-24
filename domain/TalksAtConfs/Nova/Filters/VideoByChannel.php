<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Domain\TalksAtConfs\Models\Channel;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class VideoByChannel extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, mixed $value)
    {
        return $query->where('channel_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array
     */
    public function options(Request $request)
    {
        return Channel::select(['id', 'title'])
            ->get()
            ->mapWithKeys(
                fn ($channel) => [
                    $channel->title => $channel->id,
                ]
            )->toArray();
    }
}

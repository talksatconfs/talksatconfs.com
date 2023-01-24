<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Domain\TalksAtConfs\Models\Video;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class VideoBySource extends Filter
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
        return $query->where('source', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array
     */
    public function options(Request $request)
    {
        return Video::select(['source'])
            ->groupBy('source')
            ->get()
            ->mapWithKeys(fn ($video) => [
                $video->source => $video->source,
            ])
            ->toArray();
    }
}

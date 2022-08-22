<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Domain\TalksAtConfs\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Filters\Filter;

class EventByYear extends Filter
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where(DB::raw('YEAR(`from_date`)'), $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Event::select([DB::raw('DISTINCT(YEAR(`from_date`)) AS event_year')])
        ->orderBy(DB::raw('YEAR(`from_date`)'))
        ->get()
        ->mapWithKeys(function ($row) {
            return [$row->getAttribute('event_year') => $row->getAttribute('event_year')];
        })
        ->toArray();
    }
}

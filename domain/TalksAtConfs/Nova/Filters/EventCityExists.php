<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class EventCityExists extends Filter
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
        if ($value === 'true') {
            return $query->whereNotNull('city');
        }

        return $query->whereNull('city');
    }

    /**
     * Get the filter's available options.
     *
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'True' => true,
            'False' => false,
        ];
    }
}

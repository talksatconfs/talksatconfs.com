<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class EventCountryExists extends Filter
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
        if ($value === 'true') {
            return $query->whereNotNull('country');
        }

        return $query->whereNull('country');
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
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

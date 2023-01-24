<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class VideoTitleExists extends BooleanFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, mixed $value)
    {
        if ($value['exists']) {
            $query->whereNotNull('title');
        }
        if ($value['missing']) {
            $query->whereNull('title');
        }

        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Exists' => 'exists',
            'Missing' => 'missing',
        ];
    }
}

<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Domain\TalksAtConfs\Models\Conference;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class EventsByConference extends Filter
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
        return $query->where('conference_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array
     */
    public function options(Request $request)
    {
        return Conference::select(['id', 'name'])->get()->mapWithKeys(fn ($conf) => [
            $conf->name => $conf->id,
        ])->toArray();
    }
}

<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Domain\TalksAtConfs\Models\Conference;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TalksByConference extends Filter
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
        return $query->with('conference')->whereHas('conference', function ($q) use ($value) {
            $q->where('conferences.id', $value);
        });
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

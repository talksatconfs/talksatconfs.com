<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Domain\TalksAtConfs\Models\Event;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TalksByEvent extends Filter
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
        return $query->where('event_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Event::select(['id', 'name', 'location', 'from_date'])
            ->get()
            ->mapWithKeys(
                function ($event) {
                    return [
                        $event->event_title => $event->id,
                    ];
                }
            )->toArray();
    }
}

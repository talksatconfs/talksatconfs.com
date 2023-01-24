<?php

namespace Domain\TalksAtConfs\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class MostSpeakerTalks extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->withCount('talks')
                ->orderByDesc('talks_count')
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Name'),
            Text::make('Github'),
            Text::make('Twitter'),
            Text::make('Youtube'),
            Text::make('Website'),

            Number::make('# Talks', 'talks_count'),
        ];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'most-talks';
    }
}

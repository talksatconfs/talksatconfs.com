<?php

namespace Domain\TalksAtConfs\Nova\Resources;

use App\Nova\Resource;
use Domain\TalksAtConfs\Models\Event as ModelsEvent;
use Domain\TalksAtConfs\Nova\Actions\AddTalksFromYaml;
use Domain\TalksAtConfs\Nova\Actions\ImportTalksFromCsv;
use Domain\TalksAtConfs\Nova\Filters\EventByYear;
use Domain\TalksAtConfs\Nova\Filters\EventCityExists;
use Domain\TalksAtConfs\Nova\Filters\EventCountryExists;
use Domain\TalksAtConfs\Nova\Filters\EventsByConference;
use Domain\TalksAtConfs\Nova\Filters\EventVenueExists;
use Domain\TalksAtConfs\Nova\Lenses\LeastTalks;
use Domain\TalksAtConfs\Nova\Lenses\MostTalks;
use Illuminate\Http\Request;
use Khalin\Nova\Field\Link;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Spatie\TagsField\Tags;

class Event extends Resource
{
    public static $model = ModelsEvent::class;

    public static $title = 'name';

    public static $group = 'Talks At Confs';

    public static $search = [
        'name', 'location',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Name')->sortable(),

            Markdown::make('Description'),

            Text::make('Location')->sortable(),

            Text::make('Venue')->sortable(),

            Text::make('City')->sortable(),

            Text::make('Country')->sortable(),

            Text::make('Link')->sortable(),

            Text::make('Playlist')->sortable(),

            Link::make('Playlist Url')
                ->sortable()
                ->blank()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Date::make('From Date')->sortable(),

            Date::make('To Date')->sortable(),

            // Tags::make('Tags'),

            BelongsTo::make('Conference')->searchable(),

            HasMany::make('Talks'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new EventsByConference(),
            new EventByYear(),
            new EventCityExists(),
            new EventCountryExists(),
            new EventVenueExists(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new MostTalks(),
            new LeastTalks(),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new AddTalksFromYaml(),
            new ImportTalksFromCsv(),
            new DownloadExcel(),
        ];
    }
}

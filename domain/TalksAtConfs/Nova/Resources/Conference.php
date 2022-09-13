<?php

namespace Domain\TalksAtConfs\Nova\Resources;

use App\Nova\Resource;
use Domain\TalksAtConfs\Models\Conference as ModelsConference;
use Domain\TalksAtConfs\Nova\Actions\AddEventToConferenceFromYaml;
use Domain\TalksAtConfs\Nova\Lenses\LeastTalks;
use Domain\TalksAtConfs\Nova\Lenses\MostTalks;
use Illuminate\Http\Request;
use Khalin\Nova\Field\Link;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Spatie\TagsField\Tags;

class Conference extends Resource
{
    public static $model = ModelsConference::class;

    public static $title = 'name';

    public static $group = 'Talks At Confs';

    public static $search = [
        'name', 'website',
    ];

    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Name')->sortable(),

            Markdown::make('Description'),

            Text::make('Website')->sortable(),

            Text::make('Twitter')->sortable(),

            Text::make('Channel'),

            Link::make('Channel Url')->hideWhenCreating()->hideWhenUpdating(),

            HasMany::make('Events'),

            // Tags::make('Tags'),
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
            new AddEventToConferenceFromYaml(),
            new DownloadExcel(),
        ];
    }
}

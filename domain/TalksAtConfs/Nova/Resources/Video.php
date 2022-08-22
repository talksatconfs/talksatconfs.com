<?php

namespace Domain\TalksAtConfs\Nova\Resources;

use App\Nova\Resource;
use Domain\TalksAtConfs\Models\Video as ModelsVideo;
use Domain\TalksAtConfs\Nova\Filters\VideoByChannel;
use Domain\TalksAtConfs\Nova\Filters\VideoBySource;
use Domain\TalksAtConfs\Nova\Filters\VideoTitleExists;
use Illuminate\Http\Request;
use Khalin\Nova\Field\Link;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Video extends Resource
{
    public static $model = ModelsVideo::class;

    public static $group = 'Talks At Confs';

    public static $search = [
        'key', 'title',
    ];

    public function title()
    {
        return $this->source . ': ' . $this->key . ' - ' . $this->title;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        // adds a `tags_count` column to the query result based on
        // number of tags associated with this product
        return $query->withCount('talks');
    }

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

            Text::make('Key'),
            Text::make('Source'),

            Text::make('Title'),
            Number::make('Duration'),

            Text::make('Duration For Humans')
                ->hideWhenUpdating()
                ->hideWhenCreating(),

            Text::make('Publisher At'),
            Text::make('Description')
                ->hideFromIndex(),

            Number::make('# Of Talks', 'talks_count')
                ->onlyOnIndex()
                ->sortable(),

            Link::make('Video Link')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->blank(),

            BelongsToMany::make('Talks')->searchable(),

            BelongsTo::make('Channel')->searchable(),
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
            new VideoByChannel(),
            new VideoBySource(),
            new VideoTitleExists(),
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
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}

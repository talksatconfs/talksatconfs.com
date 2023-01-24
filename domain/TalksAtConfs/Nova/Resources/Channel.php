<?php

namespace Domain\TalksAtConfs\Nova\Resources;

use App\Nova\Resource;
use Domain\TalksAtConfs\Models\Channel as ModelsChannel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Channel extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ModelsChannel::class;

    public static $group = 'Talks At Confs';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'key',
        'title',
        'description',
        'custom_url',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make('Key'),
            Text::make('Title'),
            Text::make('Description'),
            Text::make('Custom Url'),

            HasMany::make('Videos'),
        ];
    }
}

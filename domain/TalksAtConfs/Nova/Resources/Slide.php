<?php

namespace Domain\TalksAtConfs\Nova\Resources;

use App\Nova\Resource;
use Domain\TalksAtConfs\Models\Slide as ModelsSlide;
use Illuminate\Http\Request;
use Khalin\Nova\Field\Link;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;

class Slide extends Resource
{
    public static $model = ModelsSlide::class;

    public static $title = 'link';

    public static $group = 'Talks At Confs';

    public static $search = [
        'link',
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

            Link::make('Link'),

            BelongsTo::make('Talk'),
        ];
    }
}

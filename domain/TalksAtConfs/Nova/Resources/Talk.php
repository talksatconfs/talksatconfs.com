<?php

namespace Domain\TalksAtConfs\Nova\Resources;

use App\Nova\Resource;
use Domain\TalksAtConfs\Models\Talk as ModelsTalk;
use Domain\TalksAtConfs\Nova\Filters\TalksByConference;
use Domain\TalksAtConfs\Nova\Filters\TalksByEvent;
use Illuminate\Http\Request;
use Khalin\Nova\Field\Link;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Spatie\TagsField\Tags;

class Talk extends Resource
{
    public static $model = ModelsTalk::class;

    public static $title = 'title';

    public static $group = 'Talks At Confs';

    public static $search = [
        'title', 'talk_date',
    ];

    public function title()
    {
        return $this->event->name . ' : ' . $this->title;
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

            Text::make('Title')->sortable(),

            Markdown::make('Description'),

            Link::make('Link')
                ->blank(),

            DateTime::make('Talk Date')->sortable(),

            Text::make('Video Start Time')->nullable(),

            // Tags::make('Tags'),

            BelongsTo::make('Event', 'event', Event::class)->searchable(),

            BelongsToMany::make('Speakers', 'speakers', Speaker::class)->searchable(),

            BelongsToMany::make('Videos', 'videos', Video::class)->searchable(),

            HasMany::make('Slides', 'slides', Slide::class),
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
            new TalksByConference(),
            new TalksByEvent(),
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
            new DownloadExcel(),
        ];
    }
}

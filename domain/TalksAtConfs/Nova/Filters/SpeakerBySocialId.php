<?php

namespace Domain\TalksAtConfs\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class SpeakerBySocialId extends BooleanFilter
{
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
        return $query
            ->when($value['twitter'], fn ($builder) => $builder->whereNull('twitter'))
            ->when($value['github'], fn ($builder) => $builder->whereNull('github'))
            ->when($value['youtube'], fn ($builder) => $builder->whereNull('youtube'))
            ->when($value['website'], fn ($builder) => $builder->whereNull('website'));
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Twitter' => 'twitter',
            'Github' => 'github',
            'Youtube' => 'youtube',
            'Website' => 'website',
        ];
    }
}

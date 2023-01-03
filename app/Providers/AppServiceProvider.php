<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            'conference' => 'Domain\TalksAtConfs\Models\Conference',
            'event' => 'Domain\TalksAtConfs\Models\Event',
            'talk' => 'Domain\TalksAtConfs\Models\Talk',
            'video' => 'Domain\TalksAtConfs\Models\Video',
            'user' => 'App\Models\User',
        ]);

        Model::preventLazyLoading(! $this->app->isProduction());
    }
}

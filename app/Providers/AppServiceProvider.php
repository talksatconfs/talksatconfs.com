<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
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

        Gate::define('viewPulse', fn (User $user): bool => in_array(
            $user->email,
            config('pulse.admin_emails', []),
            true,
        ));
    }
}

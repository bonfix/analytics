<?php

namespace Bonfix\DaliliAnalytics;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class DaliliAnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->publishes([
            dirname(__DIR__, 1).'/config.php' => config_path('analytics.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $polyMorphsBinding = config('analytics.polymorph_bindings', []);

        Relation::morphMap($polyMorphsBinding);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Bonfix\DaliliAnalytics\AnalyticsSessionController');
    }
}

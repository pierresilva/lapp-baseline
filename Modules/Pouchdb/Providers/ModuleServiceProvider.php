<?php

namespace App\Modules\Pouchdb\Providers;

use pierresilva\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'pouchdb');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'pouchdb');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'pouchdb');
        $this->loadConfigsFrom(__DIR__.'/../config');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}

<?php

namespace pierresilva\SchemaBuilder;

use Illuminate\Support\ServiceProvider;

class SchemaBuilderServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'pierresilva\SchemaBuilder\Controllers';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources', 'schema-builder');

        $app = $this->app;
        $isLumen = str_contains($app->version(), 'Lumen');
        $isEnabled = env('SCHEMABUILDER_ROUTES_ENABLED', false) && 'local' == env('APP_ENV');

        if ($isLumen && $isEnabled) {
            $app->group(['namespace' => $this->namespace], function () use ($app, $isLumen) {
                require __DIR__ . '/routes.php';
            });
        } elseif ($isEnabled) {
            $app->router->group(['namespace' => $this->namespace], function () use ($app, $isLumen) {
                require __DIR__ . '/routes.php';
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

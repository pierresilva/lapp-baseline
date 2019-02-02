<?php

namespace pierresilva\InverseSeed;

use Illuminate\Support\ServiceProvider;

class InverseSeedServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        require base_path().'/vendor/autoload.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResources();

        $this->app->singleton('InverseSeed', function($app) {
            return new InverseSeed;
        });

        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('InverseSeed', 'pierresilva\InverseSeed\Facades\InverseSeed');
        });

        $this->app->singleton('command.inverse-seed', function($app) {
            return new InverseSeedCommand;
        });

        $this->commands('command.inverse-seed');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('InverseSeed');
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $userConfigFile    = app()->configPath().'/inverse-seed.php';
        $packageConfigFile = __DIR__.'/../config/config.php';
        $config            = $this->app['files']->getRequire($packageConfigFile);

        if (file_exists($userConfigFile)) {
            $userConfig = $this->app['files']->getRequire($userConfigFile);
            $config     = array_replace_recursive($config, $userConfig);
        }

        $this->app['config']->set('inverse-seed::config', $config);
    }
}

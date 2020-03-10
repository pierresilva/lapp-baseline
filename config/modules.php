<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Path to Modules
    |--------------------------------------------------------------------------
    |
    | Define the path where you'd like to store your modules. Note that if you
    | choose a path that's outside of your public directory, you will need to
    | copy your module assets (CSS, images, etc.) to your public directory.
    |
    */

    'path' => base_path('app/Modules'), // app_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Modules Default State
    |--------------------------------------------------------------------------
    |
    | When a previously unknown module is added, if it doesn't have an 'enabled'
    | state set then this is the value which it will default to. If this is
    | not provided then the module will default to being 'enabled'.
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Modules Base Namespace
    |--------------------------------------------------------------------------
    |
    | Define the base namespace for your modules. Be sure to update this value
    | if you move your modules directory to a new path. This is primarily used
    | by the module:make Artisan command.
    |
    */

    'namespace' => 'App\Modules\\',

    /*
    |--------------------------------------------------------------------------
    | Default Module Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the module storage driver that will be utilized.
    | This driver manages the retrieval and management of module properties.
    | Setting this to custom allows you to specify your own driver instance.
    |
    | Supported: "local" or "custom"
    |
    */

    'driver' => 'local',

    /*
     |--------------------------------------------------------------------------
     | Custom Module Driver
     |--------------------------------------------------------------------------
     |
     | Using a custom module driver, the 'driver' value need to be set to 'custom'
     | The path to the driver need to be set in addition at custom_driver.
     |
     | @warn: This value will be only considered if driver is set to custom.
     |
     */

    'custom_driver' => 'pierresilva\Modules\Repositories\LocalRepository',

    /*
    |--------------------------------------------------------------------------
    | Remap Module Subdirectories
    |--------------------------------------------------------------------------
    |
    | Redefine how module directories are structured. The mapping here will
    | be respected by all commands and generators.
    |
    */

    'pathMap' => [
        // To change where migrations go, specify the default
        // location as the key and the new location as the value:
        // 'Database/Migrations' => 'src/Database/Migrations',
        'Config'              => 'Config',
        'Database/Factories'  => 'Database/Factories',
        'Database/Migrations' => 'Database/Migrations',
        'Database/Seeds'      => 'Database/Seeds',
        'Http/Controllers'    => 'Http/Controllers',
        'Http/Middleware'     => 'Http/Middleware',
        'Providers'           => 'Providers',
        'Resources/Lang'      => 'Resources/Lang',
        'Resources/Views'     => 'Resources/Views',
        'Routes'              => 'Routes'
    ],
];

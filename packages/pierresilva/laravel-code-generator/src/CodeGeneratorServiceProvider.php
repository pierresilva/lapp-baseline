<?php

namespace pierresilva\CodeGenerator;

use pierresilva\CodeGenerator\Support\Helpers;
use File;
use Illuminate\Support\ServiceProvider;

class CodeGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $dir = __DIR__ . '/../';

        $this->publishes([
            $dir . 'config/codegenerator.php' => config_path('codegenerator.php'),
            $dir . 'templates/default' => $this->codeGeneratorBase('templates/default'),
        ], 'default');

        if (!File::exists(config_path('codegenerator_custom.php'))) {
            $this->publishes([
                $dir . 'config/codegenerator_custom.php' => config_path('codegenerator_custom.php'),
            ], 'default');
        }

        $this->publishes([
            $dir . 'templates/default-collective' => $this->codeGeneratorBase('templates/default-collective'),
        ], 'default-collective');

        $this->createDirectory($this->codeGeneratorBase('sources'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $commands = [
            'pierresilva\CodeGenerator\Commands\CreateControllerCommand',
            'pierresilva\CodeGenerator\Commands\CreateModelCommand',
            'pierresilva\CodeGenerator\Commands\CreateIndexViewCommand',
            'pierresilva\CodeGenerator\Commands\CreateCreateViewCommand',
            'pierresilva\CodeGenerator\Commands\CreateFormViewCommand',
            'pierresilva\CodeGenerator\Commands\CreateEditViewCommand',
            'pierresilva\CodeGenerator\Commands\CreateShowViewCommand',
            'pierresilva\CodeGenerator\Commands\CreateViewsCommand',
            'pierresilva\CodeGenerator\Commands\CreateLanguageCommand',
            'pierresilva\CodeGenerator\Commands\CreateFormRequestCommand',
            'pierresilva\CodeGenerator\Commands\CreateRoutesCommand',
            'pierresilva\CodeGenerator\Commands\CreateMigrationCommand',
            'pierresilva\CodeGenerator\Commands\CreateResourcesCommand',
            'pierresilva\CodeGenerator\Commands\CreateMappedResourcesCommand',
            'pierresilva\CodeGenerator\Commands\CreateViewLayoutCommand',
            'pierresilva\CodeGenerator\Commands\CreateLayoutCommand',
            'pierresilva\CodeGenerator\Commands\ResourceFileFromDatabaseCommand',
            'pierresilva\CodeGenerator\Commands\ResourceFileCreateCommand',
            'pierresilva\CodeGenerator\Commands\ResourceFileDeleteCommand',
            'pierresilva\CodeGenerator\Commands\ResourceFileAppendCommand',
            'pierresilva\CodeGenerator\Commands\ResourceFileReduceCommand',
        ];

        if (Helpers::isNewerThanOrEqualTo()) {
            $commands = array_merge($commands, [
                'pierresilva\CodeGenerator\Commands\Migrations\MigrateAllCommand',
                'pierresilva\CodeGenerator\Commands\Migrations\RefreshAllCommand',
                'pierresilva\CodeGenerator\Commands\Migrations\ResetAllCommand',
                'pierresilva\CodeGenerator\Commands\Migrations\RollbackAllCommand',
                'pierresilva\CodeGenerator\Commands\Migrations\StatusAllCommand',
            ]);
        }

        $this->commands($commands);
    }

    /**
     * Create a directory if one does not already exists
     *
     * @param string $path
     *
     * @return void
     */
    protected function createDirectory($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
        }
    }

    /**
     * Get the laravel-code-generator base path
     *
     * @param string $path
     *
     * @return string
     */
    protected function codeGeneratorBase($path = null)
    {
        return base_path('resources/laravel-code-generator/') . $path;
    }
}

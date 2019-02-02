<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Translation\Translator;

class AppRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh database, seeders, passport';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $environment = config('app.env');

        if ($environment === 'production') {
            $this->error('This command is only available in local environment!');
            return;
        }

        $this->info("Refreshing the app!");

        \Artisan::call('migrate:refresh', [
            '--seed' => true
        ]);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            \Artisan::call('passport:install', [
                '--force' => true
            ]);
        } else {
            exec('sudo rm -rf ' . storage_path() . '/oauth-*.key');
            \Artisan::call('passport:install', [
                '--force' => true
            ]);
            exec('sudo chown www-data:www-data ' . storage_path() . '/oauth-*.key');
            exec('sudo chmod 600 ' . storage_path() . '/oauth-*.key');
        }

        $this->info("App refreshed succesfuly!");

    }
}

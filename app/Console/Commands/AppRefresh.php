<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        //

        $environment = config('app.env');

        if ($environment === 'production') {
            $this->error('This command is only available in local environment!');
        }

        $this->info("Refreshing the app!");

        \Artisan::call('migrate:refresh', [
            '--seed' => true
        ]);

        \Artisan::call('passport:install', [
            '--force' => true
        ]);

        $this->info("App refreshed succesfuly!");

    }
}

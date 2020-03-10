<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CodeAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:analysis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run phploc analysis';

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
        $output = null;
        exec('vendor\bin\phploc ' . app_path() . ' --log-json=' . base_path() . '\public\phploc\phploc.json', $output);

    }
}

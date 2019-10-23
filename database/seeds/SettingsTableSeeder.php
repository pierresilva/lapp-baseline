<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Setting::create([
            'group' => 'app',
            'key' => 'name',
            'value' => 'Lapp Baseline'
        ]);
    }
}

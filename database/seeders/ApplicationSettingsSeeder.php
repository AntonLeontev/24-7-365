<?php

namespace Database\Seeders;

use App\Models\ApplicationSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicationSettings::create(['payments_start' => 2]);
    }
}

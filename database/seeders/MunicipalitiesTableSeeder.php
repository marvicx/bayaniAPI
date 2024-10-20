<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MunicipalitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the JSON file content
        $json = File::get(database_path('data/municipalities.json'));
        
        // Decode the JSON into an array
        $municipalities = json_decode($json, true);
        
        // Insert the municipalities into the database
        DB::table('municipalities')->insert($municipalities);
    }
}

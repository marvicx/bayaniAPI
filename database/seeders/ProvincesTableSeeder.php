<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the JSON file content
        $json = File::get(database_path('data/provinces.json'));
        
        // Decode the JSON into an array
        $provinces = json_decode($json, true);
        
        // Insert the provinces into the database
        DB::table('provinces')->insert($provinces);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the JSON file content
        $json = File::get(database_path('data/regions.json'));

        // Decode the JSON into an array
        $provinces = json_decode($json, true);

        // Insert the provinces into the database
        DB::table('regions')->insert($provinces);
    }
}

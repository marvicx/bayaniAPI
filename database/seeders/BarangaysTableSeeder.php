<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class BarangaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get the JSON file content
        $json = File::get(database_path('data/barangays.json'));
        
        // Decode the JSON into an array
        $barangays = json_decode($json, true);

        // Insert the barangays into the database in chunks
        $chunks = array_chunk($barangays, 500); // Adjust the chunk size as needed
        
        foreach ($chunks as $chunk) {
            DB::table('barangays')->insert($chunk);
        }
    }
}

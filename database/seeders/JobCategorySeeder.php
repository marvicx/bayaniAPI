<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobCategorySeeder extends Seeder
{
    public function run()
    {
        $jobCategories = [
            'Information Technology (IT)',
            'Healthcare & Medical',
            'Engineering',
            'Finance & Accounting',
            'Sales & Marketing',
            'Education & Training',
            'Customer Service',
            'Administration & Office Support',
            'Human Resources (HR)',
            'Arts, Design, & Entertainment',
            'Manufacturing & Production',
            'Legal',
            'Research & Development (R&D)',
            'Transportation & Logistics',
            'Construction & Real Estate',
            'Hospitality & Tourism',
            'Public Sector & Government',
            'Retail',
            'Science & Environmental',
            'Agriculture & Farming',
        ];

        foreach ($jobCategories as $category) {
            DB::table('job_categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

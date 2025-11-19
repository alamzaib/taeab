<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobType;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobTypes = [
            ['name' => 'Contract', 'sort_order' => 1],
            ['name' => 'Full Time', 'sort_order' => 2],
            ['name' => 'Part Time', 'sort_order' => 3],
            ['name' => 'Remote', 'sort_order' => 4],
            ['name' => 'Any', 'sort_order' => 5],
        ];

        foreach ($jobTypes as $jobType) {
            JobType::updateOrCreate(
                ['name' => $jobType['name']],
                $jobType
            );
        }
    }
}

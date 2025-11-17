<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        if ($companies->count() < 10) {
            Company::factory()->count(10 - $companies->count())->create();
            $companies = Company::all();
        }

        $agents = Agent::all();
        if ($agents->isEmpty()) {
            Agent::factory()->count(5)->create();
            $agents = Agent::all();
        }

        $jobTitles = [
            'Senior Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Engineer',
            'Product Manager',
            'UI/UX Designer',
            'Mobile Engineer',
            'DevOps Engineer',
            'QA Tester',
            'Business Analyst',
        ];

        Job::factory()->count(50)->make()->each(function ($job) use ($companies, $agents, $jobTitles) {
            $company = $companies->random();
            $agent = $agents->random();

            $title = collect($jobTitles)->random();
            $job->title = $title;
            $job->slug = $this->generateUniqueSlug($title);
            $job->company_id = $company->id;
            $job->agent_id = $agent->id;
            $job->status = 'published';
            $job->posted_at = now()->subDays(rand(0, 60));
            $job->save();
        });
    }

    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Job::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}


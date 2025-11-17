<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'slug' => $this->faker->unique()->slug(),
            'location' => $this->faker->city(),
            'job_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Remote']),
            'experience_level' => $this->faker->randomElement(['Entry', 'Mid', 'Senior']),
            'salary_min' => $this->faker->numberBetween(4000, 12000),
            'salary_max' => $this->faker->numberBetween(12001, 25000),
            'short_description' => $this->faker->sentence(10),
            'description' => $this->faker->paragraph(5),
            'requirements' => $this->faker->paragraph(3),
            'status' => 'draft',
        ];
    }
}


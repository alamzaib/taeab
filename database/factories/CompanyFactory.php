<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company_name' => $this->faker->company(),
            'company_size' => $this->faker->randomElement(['1-10','11-50','51-200']),
            'industry' => $this->faker->word(),
            'website' => $this->faker->url(),
            'status' => 'active',
            'password' => bcrypt('password'),
        ];
    }
}


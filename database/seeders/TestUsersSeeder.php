<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\Seeker;
use App\Models\Company;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Test Agent
        Agent::create([
            'name' => 'Test Agent',
            'email' => 'agent@test.com',
            'password' => Hash::make('Test@1234'),
            'phone' => '+971501234567',
            'status' => 'active',
        ]);

        // Create Test Seeker
        Seeker::create([
            'name' => 'Test Seeker',
            'email' => 'seeker@test.com',
            'password' => Hash::make('Test@1234'),
            'phone' => '+971501234568',
            'status' => 'active',
        ]);

        // Create Test Company
        Company::create([
            'name' => 'Test Company Admin',
            'email' => 'company@test.com',
            'password' => Hash::make('Test@1234'),
            'phone' => '+971501234569',
            'company_name' => 'Test Company LLC',
            'company_size' => '51-200',
            'industry' => 'Technology',
            'website' => 'https://testcompany.com',
            'status' => 'active',
        ]);

        // Create Test Admin
        Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Test@1234'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->command->info('Test accounts created successfully!');
        $this->command->info('Agent: agent@test.com / Test@1234');
        $this->command->info('Seeker: seeker@test.com / Test@1234');
        $this->command->info('Company: company@test.com / Test@1234');
        $this->command->info('Admin: admin@test.com / Test@1234');
    }
}


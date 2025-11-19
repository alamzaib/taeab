<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'free',
                'display_name' => 'Free',
                'description' => 'Basic package with essential features',
                'price' => 0.00,
                'features' => ['Basic job posting', 'Limited applications', 'Company profile'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'silver',
                'display_name' => 'Silver',
                'description' => 'Enhanced features for growing companies',
                'price' => 99.00,
                'features' => ['Unlimited job postings', 'Priority support', 'Advanced analytics', 'Featured listings'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'gold',
                'display_name' => 'Gold',
                'description' => 'Premium package with advanced features',
                'price' => 199.00,
                'features' => ['All Silver features', 'Custom branding', 'API access', 'Dedicated account manager'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'platinum',
                'display_name' => 'Platinum',
                'description' => 'Enterprise solution with full features',
                'price' => 399.00,
                'features' => ['All Gold features', 'White-label solution', 'Custom integrations', '24/7 priority support'],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'custom',
                'display_name' => 'Custom',
                'description' => 'Tailored package for your specific needs',
                'price' => 0.00,
                'features' => ['Custom features', 'Flexible pricing', 'Personalized solution'],
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}

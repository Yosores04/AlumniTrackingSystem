<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only run in central context, not in tenant databases
        if (App::bound('tenant')) {
            // We're in a tenant context, don't create plans
            return;
        }
        
        // Free Plan
        Plan::updateOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free Plan',
                'description' => 'Basic alumni tracking for small schools',
                'monthly_price' => 0,
                'annual_price' => 0,
                'max_alumni' => 100,
                'max_instructors' => 1,
                'has_custom_fields' => false,
                'has_advanced_analytics' => false,
                'has_integrations' => false,
                'has_job_board' => false,
                'has_custom_branding' => false,
                'support_level' => 'community',
                'is_active' => true
            ]
        );

        // Basic Plan
        Plan::updateOrCreate(
            ['slug' => 'basic'],
            [
                'name' => 'Basic Plan',
                'description' => 'Enhanced alumni tracking with more features',
                'monthly_price' => 29,
                'annual_price' => 278.40, // 20% discount on annual billing
                'max_alumni' => 500,
                'max_instructors' => 10,
                'has_custom_fields' => true,
                'has_advanced_analytics' => false,
                'has_integrations' => false,
                'has_job_board' => false,
                'has_custom_branding' => false,
                'support_level' => 'email',
                'is_active' => true
            ]
        );

        // Premium Plan
        Plan::updateOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium Plan',
                'description' => 'Complete alumni tracking solution with all features',
                'monthly_price' => 79,
                'annual_price' => 758.40, // 20% discount on annual billing
                'max_alumni' => 0, // Unlimited
                'max_instructors' => 0, // Unlimited
                'has_custom_fields' => true,
                'has_advanced_analytics' => true,
                'has_integrations' => true,
                'has_job_board' => true,
                'has_custom_branding' => true,
                'support_level' => 'priority',
                'is_active' => true
            ]
        );
        
        // Check for plans with null slug and fix them
        $nullSlugPlans = Plan::whereNull('slug')->get();
        foreach ($nullSlugPlans as $plan) {
            // Generate a slug based on name or delete if appropriate
            if ($plan->name) {
                $slug = \Illuminate\Support\Str::slug($plan->name);
                $plan->slug = $slug;
                $plan->save();
            } else {
                // If there's no name, we can't generate a slug, so delete the invalid record
                $plan->delete();
            }
        }
    }
} 
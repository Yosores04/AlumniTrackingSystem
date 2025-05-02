<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if we're in a tenant context
        if (App::bound('tenant')) {
            // We're in a tenant context, run the tenant-specific seeder
            $this->call(TenantDatabaseSeeder::class);
        } else {
            // We're in the central context, create central-specific data
            User::factory()->create([
                'name' => 'Central Admin',
                'email' => 'admin@central.com',
                'role' => User::ROLE_CENTRAL_ADMIN,
            ]);
        }

        // Add new seeder
        $this->call([
            PlanSeeder::class,
            // ... other seeders if they exist ...
        ]);
    }
}

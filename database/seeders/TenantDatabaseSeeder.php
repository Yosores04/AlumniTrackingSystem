<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the tenant's database.
     */
    public function run(): void
    {
        // Create a default admin user for each tenant
        User::create([
            'name' => 'Tenant Admin',
            'email' => 'admin@' . tenant('id') . '.com',
            'password' => Hash::make('password'),
        ]);

        // You can call other seeders here
        // $this->call([
        //     ProductSeeder::class,
        //     CategorySeeder::class,
        // ]);
    }
}

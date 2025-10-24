<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CentralAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a central admin user for accessing the central domain.
     */
    public function run(): void
    {
        // Check if central admin already exists
        $existingAdmin = User::where('role', User::ROLE_CENTRAL_ADMIN)->first();

        if ($existingAdmin) {
            $this->command->info('Central admin already exists: ' . $existingAdmin->email);
            return;
        }

        // Create central admin
        $admin = User::create([
            'name' => 'Central Administrator',
            'email' => 'admin@buksu.edu.ph',
            'password' => Hash::make('Admin@BukSU2024'),
            'role' => User::ROLE_CENTRAL_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Central admin created successfully!');
        $this->command->info('Email: ' . $admin->email);
        $this->command->info('Password: Admin@BukSU2024');
        $this->command->warn('⚠️  Please change this password after first login!');
    }
}

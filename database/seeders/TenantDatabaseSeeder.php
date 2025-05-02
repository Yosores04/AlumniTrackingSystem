<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TenantSettings;
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
            'role' => User::ROLE_TENANT_ADMIN,
        ]);

        // Create default tenant settings
        TenantSettings::create([
            'site_name' => tenant('id') . ' Alumni Network',
            'site_description' => 'Welcome to ' . tenant('id') . ' Alumni Tracking System',
            'primary_color' => '#F53003',
            'secondary_color' => '#1B1B18',
            'accent_color' => '#3E3E3A',
            'background_color' => '#FDFDFC',
            'text_color' => '#1B1B18',
            'welcome_message' => 'Welcome to our alumni network. Connect with fellow graduates, stay updated on events, and grow your professional network.',
            'footer_text' => 'Our alumni network helps graduates stay connected and informed about the latest opportunities.',
            'show_social_links' => true,
            'is_public' => true,
        ]);

        // You can call other seeders here
        // $this->call([
        //     ProductSeeder::class,
        //     CategorySeeder::class,
        // ]);
    }
}

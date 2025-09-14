<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestPasswordChange extends Command
{
    protected $signature = 'test:password-change {email}';
    protected $description = 'Test the password change functionality';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing password change functionality for: $email");
        
        // Find or create a test user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => $email,
                'password' => Hash::make('oldpassword123'),
                'email_verified_at' => now(),
            ]);
            $this->info("Created test user: $email");
        }
        
        $originalPassword = $user->password;
        $this->info("Original password hash: " . substr($originalPassword, 0, 20) . "...");
        
        // Test password change
        $newPassword = 'NewSecurePassword123!';
        $user->password = Hash::make($newPassword);
        $user->save();
        
        $this->info("Updated password hash: " . substr($user->password, 0, 20) . "...");
        
        // Verify the change
        if ($originalPassword !== $user->password) {
            $this->info("✅ Password hash changed successfully");
        } else {
            $this->error("❌ Password hash did not change");
        }
        
        // Verify password check works
        if (Hash::check($newPassword, $user->password)) {
            $this->info("✅ New password verification successful");
        } else {
            $this->error("❌ New password verification failed");
        }
        
        // Test old password no longer works
        if (!Hash::check('oldpassword123', $user->password)) {
            $this->info("✅ Old password correctly rejected");
        } else {
            $this->error("❌ Old password still works (shouldn't happen)");
        }
        
        $this->info("Password change test completed!");
        
        return 0;
    }
}
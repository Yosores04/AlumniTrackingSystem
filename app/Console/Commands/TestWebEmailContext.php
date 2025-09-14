<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Notifications\DomainRequestApproved;
use App\Models\User;
use Stancl\Tenancy\Facades\Tenancy;

class TestWebEmailContext extends Command
{
    protected $signature = 'test:web-email-context {email}';
    protected $description = 'Test email sending in both artisan and web-like context';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing email context differences...");
        
        // Test 1: Direct Mail facade (like artisan commands)
        $this->info("1. Testing direct Mail facade...");
        try {
            Mail::raw('Test from direct Mail facade', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Direct Mail');
            });
            $this->info("✅ Direct Mail facade sent");
        } catch (\Exception $e) {
            $this->error("❌ Direct Mail failed: " . $e->getMessage());
        }

        // Test 2: Notification facade (like web requests)
        $this->info("2. Testing Notification class...");
        try {
            // Create a temporary user
            $user = new User([
                'email' => $email,
                'name' => 'Test User'
            ]);
            
            $user->notify(new DomainRequestApproved([
                'name' => 'Test User',
                'domain' => 'test.localhost',
                'login_url' => 'http://test.localhost:8000/login',
                'email' => $email,
                'password' => 'testpassword123'
            ]));
            $this->info("✅ Notification sent");
        } catch (\Exception $e) {
            $this->error("❌ Notification failed: " . $e->getMessage());
        }

        // Test 3: Check mail configuration
        $this->info("3. Mail configuration:");
        $this->info("Driver: " . config('mail.default'));
        $this->info("Host: " . config('mail.mailers.smtp.host'));
        $this->info("Queue: " . config('queue.default'));
        
        // Test 4: Check if we're in tenant context
        $this->info("4. Context check:");
        if (tenant()) {
            $this->info("Tenant: " . tenant()->id);
        } else {
            $this->info("Central context");
        }
        
        $this->info("Test completed!");
    }
}
<?php

namespace App\Console\Commands;

use App\Models\DomainRequest;
use App\Notifications\DomainRequestApproved;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class TestEmailSystem extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Test the email system by sending a test domain approval notification';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing email system by sending to: {$email}");
        
        // Create test credentials
        $credentials = [
            'domain' => 'test.localhost',
            'name' => 'Test User',
            'email' => $email,
            'password' => 'TestPassword123',
            'login_url' => 'http://test.localhost:8000',
        ];
        
        try {
            $this->info("Attempting to send test email...");
            
            Notification::route('mail', $email)
                ->notify(new DomainRequestApproved($credentials));
                
            $this->info("✅ Test email sent successfully!");
            $this->info("Check your inbox at: {$email}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            Log::error('Test email failed', ['error' => $e->getMessage()]);
            return 1;
        }
        
        return 0;
    }
}

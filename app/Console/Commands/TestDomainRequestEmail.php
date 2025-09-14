<?php

namespace App\Console\Commands;

use App\Models\DomainRequest;
use App\Notifications\DomainRequestReceived;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class TestDomainRequestEmail extends Command
{
    protected $signature = 'test:domain-request-email {email}';
    protected $description = 'Test domain request email notification specifically';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing domain request email notification to: {$email}");
        
        // Create a test domain request (not saved to database)
        $domainRequest = new DomainRequest([
            'admin_name' => 'Test User',
            'admin_email' => $email,
            'domain_prefix' => 'testdomain',
            'status' => 'pending',
        ]);
        $domainRequest->id = 999; // Set a test ID
        
        try {
            $this->info("Attempting to send domain request received notification...");
            
            Log::info('Testing domain request notification', [
                'email' => $email,
                'domain_prefix' => 'testdomain'
            ]);
            
            Notification::route('mail', $email)
                ->notify(new DomainRequestReceived($domainRequest));
                
            $this->info("✅ Domain request email sent successfully!");
            $this->info("Check your inbox at: {$email}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send domain request email: " . $e->getMessage());
            Log::error('Test domain request email failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
        
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DomainRequest;
use App\Models\User;
use App\Notifications\DomainRequestReceived;

class TestDomainRequestFlow extends Command
{
    protected $signature = 'test:domain-request-flow {email}';
    protected $description = 'Test the complete domain request flow';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing domain request flow...");
        
        try {
            // Create a domain request
            $domainRequest = new DomainRequest([
                'admin_name' => 'Test Admin',
                'admin_email' => $email,
                'domain_prefix' => 'flowtest',
                'status' => 'pending'
            ]);
            $domainRequest->save();
            
            $this->info("Domain request created with ID: " . $domainRequest->id);
            
            // Create a temporary user for notification
            $user = new User([
                'email' => $email,
                'name' => 'Test User'
            ]);
            
            // Send the notification
            $this->info("Sending notification...");
            $user->notify(new DomainRequestReceived($domainRequest));
            $this->info("✅ Notification sent successfully");
            
            // Clean up
            $domainRequest->delete();
            $this->info("Test domain request cleaned up");
            
        } catch (\Exception $e) {
            $this->error("❌ Test failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
        
        $this->info("Test completed!");
    }
}
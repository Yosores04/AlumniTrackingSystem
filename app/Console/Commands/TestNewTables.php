<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumni;
use App\Models\SocialAccount;
use App\Models\TrackingStatus;
use App\Models\Attachment;

class TestNewTables extends Command
{
    protected $signature = 'test:new-tables';
    protected $description = 'Test if the new tables are working by attempting to create test records';

    public function handle()
    {
        try {
            // Test if we can query the Alumni table (should exist)
            $alumniCount = Alumni::count();
            $this->info("Found {$alumniCount} alumni records");
            
            if ($alumniCount > 0) {
                $alumni = Alumni::first();
                $this->info("Testing with alumni: {$alumni->first_name} {$alumni->last_name}");
                
                // Test SocialAccount model
                try {
                    $socialAccountCount = SocialAccount::where('alumni_id', $alumni->id)->count();
                    $this->info("✓ SocialAccount table is accessible. Found {$socialAccountCount} records for this alumni.");
                } catch (\Exception $e) {
                    $this->error("✗ SocialAccount table issue: " . $e->getMessage());
                }
                
                // Test TrackingStatus model
                try {
                    $trackingStatusCount = TrackingStatus::where('alumni_id', $alumni->id)->count();
                    $this->info("✓ TrackingStatus table is accessible. Found {$trackingStatusCount} records for this alumni.");
                } catch (\Exception $e) {
                    $this->error("✗ TrackingStatus table issue: " . $e->getMessage());
                }
                
                // Test Attachment model
                try {
                    $attachmentCount = Attachment::where('attachable_type', Alumni::class)
                        ->where('attachable_id', $alumni->id)->count();
                    $this->info("✓ Attachment table is accessible. Found {$attachmentCount} records for this alumni.");
                } catch (\Exception $e) {
                    $this->error("✗ Attachment table issue: " . $e->getMessage());
                }
            } else {
                $this->error("No alumni records found to test with");
            }
            
        } catch (\Exception $e) {
            $this->error("General error: " . $e->getMessage());
        }
        
        return 0;
    }
}
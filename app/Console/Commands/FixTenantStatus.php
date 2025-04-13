<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class FixTenantStatus extends Command
{
    protected $signature = 'tenants:fix-status';
    protected $description = 'Check and fix tenant status data';

    public function handle()
    {
        $this->info('Checking tenant status data...');
        
        // Get all tenants
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->warn('No tenants found in database.');
            return Command::SUCCESS;
        }
        
        $fixed = 0;
        
        foreach ($tenants as $tenant) {
            $this->info("Checking tenant: {$tenant->id}");
            
            // Display current data
            $this->line("  Original data: " . json_encode($tenant->data));
            
            // Check if data is an array
            $data = $tenant->data;
            if (!is_array($data)) {
                // Try to decode if it's a JSON string
                if (is_string($data)) {
                    $data = json_decode($data, true) ?? [];
                } else {
                    $data = [];
                }
            }
            
            $needsUpdate = false;
            
            // Check if status exists in data
            if (!isset($data['status'])) {
                $data['status'] = 'active';
                $needsUpdate = true;
                $this->warn("  Status not found, setting to 'active'");
            } else {
                $this->info("  Current status: {$data['status']}");
            }
            
            // Update the tenant if needed
            if ($needsUpdate) {
                $tenant->data = $data;
                $tenant->save();
                $fixed++;
                $this->info("  Updated tenant data: " . json_encode($tenant->data));
            } else {
                $this->info("  No changes needed");
            }
            
            $this->newLine();
        }
        
        $this->info("Tenant status check completed. Fixed {$fixed} of {$tenants->count()} tenants.");
        
        return Command::SUCCESS;
    }
}

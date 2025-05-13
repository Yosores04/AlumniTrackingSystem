<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CheckTenantSystemVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:check-versions {tenant? : Check a specific tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if system_versions table exists and has records in tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificTenant = $this->argument('tenant');
        
        if ($specificTenant) {
            $tenants = Tenant::where('id', $specificTenant)->get();
        } else {
            $tenants = Tenant::all();
        }
        
        if ($tenants->isEmpty()) {
            $this->error('No tenants found');
            return Command::FAILURE;
        }
        
        $this->info('Checking system_versions table in ' . $tenants->count() . ' tenant databases...');
        
        $hasErrors = false;
        
        foreach ($tenants as $tenant) {
            $this->line("\nChecking tenant: {$tenant->id}");
            
            try {
                // Initialize tenant
                tenancy()->initialize($tenant);
                
                // Check if system_versions table exists
                $tableExists = false;
                try {
                    // This will throw an exception if the table doesn't exist
                    DB::table('system_versions')->limit(1)->get();
                    $tableExists = true;
                } catch (\Exception $e) {
                    // Table doesn't exist
                }
                
                if (!$tableExists) {
                    $this->warn("  ❌ system_versions table does not exist");
                    $hasErrors = true;
                    continue;
                }
                
                $this->info("  ✅ system_versions table exists");
                
                // Check number of records
                $count = DB::table('system_versions')->count();
                $this->line("  - Records: {$count}");
                
                if ($count === 0) {
                    $this->warn("  ⚠️ No system version records found");
                    $hasErrors = true;
                } else {
                    // Get current version
                    $currentVersion = DB::table('system_versions')
                        ->where('is_current', true)
                        ->first();
                    
                    if ($currentVersion) {
                        $this->info("  ✅ Current version: {$currentVersion->version}");
                    } else {
                        $this->warn("  ⚠️ No current version set");
                        $hasErrors = true;
                    }
                    
                    // Show all versions
                    $versions = DB::table('system_versions')->get();
                    $this->line("  - Available versions:");
                    foreach ($versions as $version) {
                        $status = $version->is_current ? '[CURRENT]' : ($version->is_active ? '[ACTIVE]' : '[INACTIVE]');
                        $this->line("    • {$version->version} {$status}");
                    }
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Error checking tenant: {$e->getMessage()}");
                $hasErrors = true;
            } finally {
                // End tenancy for this tenant
                tenancy()->end();
            }
        }
        
        if ($hasErrors) {
            $this->warn("\nSome tenants have issues with their system_versions table.");
            $this->line("You can fix this by running: php artisan tenants:migrate --path=database/migrations/tenant");
            return Command::FAILURE;
        } else {
            $this->info("\nAll tenant system_versions tables are properly set up.");
            return Command::SUCCESS;
        }
    }
} 
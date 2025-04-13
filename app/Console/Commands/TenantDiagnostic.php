<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TenantDiagnostic extends Command
{
    protected $signature = 'tenant:diagnostic {domain?}';
    protected $description = 'Diagnose tenant configuration issues';

    public function handle()
    {
        $this->info("=== Tenant Configuration Diagnostic ===");
        
        // Check if domain exists
        $domain = $this->argument('domain');
        if ($domain) {
            $this->info("\nChecking domain: $domain");
            $domainRecord = DB::table('domains')->where('domain', $domain)->first();
            
            if ($domainRecord) {
                $this->info("✓ Domain found in database");
                $this->info("  Tenant ID: {$domainRecord->tenant_id}");
                
                $tenant = Tenant::find($domainRecord->tenant_id);
                if ($tenant) {
                    $this->info("✓ Tenant exists");
                } else {
                    $this->error("✗ Tenant {$domainRecord->tenant_id} not found in database");
                }
            } else {
                $this->error("✗ Domain not found in database");
            }
        }
        
        // Check central domains configuration
        $this->info("\nCentral domains:");
        $centralDomains = config('tenancy.central_domains');
        foreach ($centralDomains as $centralDomain) {
            $this->info("  - $centralDomain");
        }
        
        // Check tenant routes
        $this->info("\nTenant routes file:");
        if (file_exists(base_path('routes/tenant.php'))) {
            $this->info("✓ routes/tenant.php exists");
        } else {
            $this->error("✗ routes/tenant.php not found");
        }
        
        // Check bootstrappers
        $this->info("\nTenant bootstrappers:");
        $bootstrappers = config('tenancy.bootstrappers');
        foreach ($bootstrappers as $bootstrapper) {
            $this->info("  - " . class_basename($bootstrapper));
        }
        
        // Check tenant service provider is registered
        $this->info("\nProviders:");
        if (array_key_exists('App\Providers\TenancyServiceProvider', app()->getLoadedProviders())) {
            $this->info("✓ TenancyServiceProvider is loaded");
        } else {
            $this->warn("? TenancyServiceProvider may not be loaded");
        }
        
        $this->info("\nTenants in database:");
        $tenants = Tenant::all();
        if ($tenants->isEmpty()) {
            $this->warn("No tenants found in database");
        } else {
            foreach ($tenants as $tenant) {
                $this->info("  - {$tenant->id}");
                $domains = $tenant->domains;
                if ($domains->isEmpty()) {
                    $this->warn("    No domains for this tenant");
                } else {
                    foreach ($domains as $domain) {
                        $this->info("    Domain: {$domain->domain}");
                    }
                }
            }
        }
        
        return Command::SUCCESS;
    }
}

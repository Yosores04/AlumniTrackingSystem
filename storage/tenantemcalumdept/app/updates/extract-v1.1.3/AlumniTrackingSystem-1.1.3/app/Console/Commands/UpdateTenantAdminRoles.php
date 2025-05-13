<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\User;

class UpdateTenantAdminRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:update-admin-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing tenant admin users to have the tenant_admin role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->info('No tenants found.');
            return;
        }
        
        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->id}");
            
            tenancy()->initialize($tenant);
            
            // Update the admin user (assuming it's the first user in the tenant database)
            $adminUpdated = DB::table('users')
                ->where('email', 'like', 'admin@%')
                ->update(['role' => User::ROLE_TENANT_ADMIN]);
            
            $this->info("Updated {$adminUpdated} admin users for tenant {$tenant->id}");
            
            tenancy()->end();
        }
        
        // Update central admin(s)
        $this->info("Updating central admin(s)...");
        $centralAdminUpdated = DB::table('users')
            ->where('email', 'admin@central.com')
            ->update(['role' => User::ROLE_CENTRAL_ADMIN]);
            
        $this->info("Updated {$centralAdminUpdated} central admin users");
        
        $this->info('Admin roles update completed!');
    }
} 
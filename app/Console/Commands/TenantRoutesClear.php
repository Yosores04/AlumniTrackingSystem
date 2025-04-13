<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantRoutesClear extends Command
{
    protected $signature = 'tenant:routes-clear';
    protected $description = 'Clear route cache and reload tenant routes';

    public function handle()
    {
        $this->info('Clearing route cache...');
        Artisan::call('route:clear');
        $this->info('Route cache cleared!');
        
        $this->info('Reloading routes...');
        
        // Force reload of tenant routes by touching the tenant.php file
        if (file_exists(base_path('routes/tenant.php'))) {
            touch(base_path('routes/tenant.php'));
            $this->info('Tenant routes reloaded!');
        } else {
            $this->error('routes/tenant.php not found!');
        }
        
        return Command::SUCCESS;
    }
}

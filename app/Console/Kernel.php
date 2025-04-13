<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use App\Models\Tenant;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CreateTenant::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run tenant-specific commands
        $this->scheduleTenantCommands($schedule);
        
        // Central commands
        $schedule->command('app:central-reports-generate')
                 ->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
    /**
     * Schedule commands for each tenant
     */
    protected function scheduleTenantCommands(Schedule $schedule): void
    {
        // Get all active tenants
        $tenants = Tenant::where('status', 'active')->get();
        
        foreach ($tenants as $tenant) {
            // Skip manually suspended tenants via cache
            if (Cache::has("tenant:{$tenant->id}:suspended")) {
                continue;
            }
            
            // Schedule tenant-specific commands
            $schedule->command("tenants:run {$tenant->id} --command='app:generate-reports'")
                     ->weekdays()
                     ->dailyAt('01:00');
                     
            $schedule->command("tenants:run {$tenant->id} --command='app:data-backup'")
                     ->weekly()
                     ->sundays()
                     ->at('23:00');
        }
    }
}
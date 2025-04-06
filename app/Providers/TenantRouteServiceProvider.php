<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class TenantRouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This provider explicitly loads the tenant routes from routes/tenant.php
        $this->loadTenantRoutes();
    }

    /**
     * Load the tenant routes
     */
    protected function loadTenantRoutes(): void
    {
        if (file_exists(base_path('routes/tenant.php'))) {
            Log::info('TenantRouteServiceProvider loading tenant routes');
            
            Route::middleware('web')
                ->group(base_path('routes/tenant.php'));
        } else {
            Log::warning('routes/tenant.php file not found');
        }
    }
}

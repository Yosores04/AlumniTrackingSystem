<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Observers\TenantObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\TenantSettings;

class AppServiceProvider extends ServiceProvider
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

        // Register tenant observer
        Tenant::observe(TenantObserver::class);

        // Share tenant settings with all views
        View::composer('*', function ($view) {
            if (app()->bound('tenancy') && tenancy()->initialized) {
                $settings = TenantSettings::getSettings();
                $view->with('settings', $settings);
            }
        });

    }
}

<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Observers\TenantObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\TenantSettings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind a service to check if we're in a tenant context
        $this->app->singleton('currentTenant', function ($app) {
            return tenant();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register tenant observer
        Tenant::observe(TenantObserver::class);

        // Pass tenant settings to all tenant views
        View::composer('tenant.*', function ($view) {
            $settings = TenantSettings::getSettings();
            $view->with('settings', $settings);
        });
        
        // Pass tenant flag to all views
        View::composer('*', function ($view) {
            $view->with('isTenant', app('currentTenant') ? true : false);
        });

        // Create a blade directive for tenant-aware asset paths
        Blade::directive('tenantAsset', function ($expression) {
            return "<?php echo app('currentTenant') ? secure_asset($expression) : asset($expression); ?>";
        });

        // Force HTTPS in production
        if (App::environment('production')) {
            URL::forceScheme('https');
        }
    }
}

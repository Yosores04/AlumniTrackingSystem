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
use Illuminate\Support\Facades\Log;

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

        // Register the subscription singleton
        $this->app->singleton('subscription', function ($app) {
            return function($passable, $next) use ($app) {
                // Simply pass through to the CheckSubscription middleware
                return $app->make(\App\Http\Middleware\CheckSubscription::class)
                          ->handle($passable, $next, $app->request->route()->parameter('subscription'));
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register tenant observer
        Tenant::observe(TenantObserver::class);

        // Register custom Blade components
        Blade::component('central-app-layout', \App\View\Components\CentralAppLayout::class);
        Blade::component('instructor-layout', \App\View\Components\InstructorLayout::class);

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

        // Add any custom blade directives here
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
        
        Blade::if('tenant_admin', function () {
            return auth()->check() && auth()->user()->isTenantAdmin();
        });
        
        Blade::if('instructor', function () {
            return auth()->check() && auth()->user()->isInstructor();
        });
    }
}

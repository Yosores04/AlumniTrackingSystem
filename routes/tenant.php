<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Middleware\InitializeTenancy;
use App\Models\TenantSettings;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancy::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
<<<<<<< Updated upstream
        $settings = TenantSettings::getSettings();
        return view('tenant.landing', compact('settings'));
    });
    
    // Include authentication routes for tenants
    require __DIR__.'/auth.php';
    
    // Authenticated routes
    Route::middleware(['auth'])->group(function () {
        // Dashboard route
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
        
        // Admin routes for tenant settings
        Route::name('tenant.')->group(function () {
            // Settings routes
            Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [TenantSettingsController::class, 'update'])->name('settings.update');
            
            // Profile routes - these would typically use a ProfileController
            Route::get('/profile', function() {
                return view('tenant.profile.edit');
            })->name('profile.edit');
            
            // Job routes - these would typically use a JobController
            Route::get('/jobs', function() {
                return view('tenant.jobs.index');
            })->name('jobs.index');
            
            // Events routes - these would typically use an EventController
            Route::get('/events', function() {
                return view('tenant.events.index');
            })->name('events.index');
            Route::get('/events/{event}', function($event) {
                return view('tenant.events.show', ['event' => $event]);
            })->name('events.show');
            
            // News routes - these would typically use a NewsController
            Route::get('/news', function() {
                return view('tenant.news.index');
            })->name('news.index');
            
            // Directory routes - these would typically use a DirectoryController
            Route::get('/directory', function() {
                return view('tenant.directory.index');
            })->name('directory.index');
        });
=======
        // Check if tenant is in read-only mode (e.g., suspended but still accessible for data viewing)
        $readOnly = false;
        $warningMessage = null;
        
        if (tenant() && isset(tenant()->data['status']) && tenant()->data['status'] !== 'active') {
            $readOnly = true;
            $warningMessage = 'This account is currently ' . tenant()->data['status'] . '. Some features may be unavailable.';
        } elseif (tenant() && isset(tenant()->subscription['plan']) && tenant()->subscription['plan'] === 'free') {
            $warningMessage = 'You are using a free plan with limited features. Upgrade for full access.';
        }
        
        return view('tenant.welcome', [
            'readOnly' => $readOnly,
            'warningMessage' => $warningMessage
        ]);
>>>>>>> Stashed changes
    });
    
    Route::get('/debug', function () {
        // Check if tenant is in read-only mode
        $readOnly = tenant() && isset(tenant()->data['status']) && tenant()->data['status'] !== 'active';
        
        return [
            'tenant_id' => tenant('id'),
            'domain' => request()->getHost(),
            'database_connection' => config('database.default'),
            'tenant_database' => config('database.connections.tenant.database') ?? 'Not set',
            'time' => now()->format('Y-m-d H:i:s'),
            'status' => tenant()->data['status'] ?? 'active',
            'read_only' => $readOnly,
            'subscription' => tenant()->subscription ?? ['plan' => 'free']
        ];
    });
});

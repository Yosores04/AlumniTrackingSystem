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
            Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [TenantSettingsController::class, 'update'])->name('settings.update');
        });
    });
    
    // Add a diagnostic route
    Route::get('/debug', function () {
        return [
            'tenant_id' => tenant('id') ?? 'none',
            'domain' => request()->getHost(),
            'database_connection' => config('database.default'),
            'tenant_database' => config('database.connections.tenant.database') ?? 'Not set',
            'is_tenant_context' => app()->bound('tenant'),
        ];
    });
});

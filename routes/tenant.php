<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\InitializeTenancy;
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
        return 'This is tenant: ' . tenant('id');
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

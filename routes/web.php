<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DomainRequestController;
use Illuminate\Support\Facades\Route;

// Define central domain routes - fixed format for domain constraints
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return view('auth.login');
        });

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        require __DIR__.'/auth.php';
    });
}

// Fallback for debugging - will only trigger if no other routes match
Route::get('/__debug', function () {
    return [
        'current_domain' => request()->getHost(),
        'central_domains' => config('tenancy.central_domains'),
        'is_tenant_domain' => !in_array(request()->getHost(), config('tenancy.central_domains')),
        'route_list' => collect(Route::getRoutes())->map(function($route) {
            return [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'domain' => $route->getDomain(),
            ];
        })->toArray(),
    ];
});

// Public Tenant Management Routes - No Authentication Required
Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');

// Google OAuth Routes with proper naming
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Debug route to check tenant status (must come BEFORE the resource route)
Route::get('/debug-tenants', function() {
    $tenants = \App\Models\Tenant::with('domains')->get();
    dd($tenants->map(function($tenant) {
        return [
            'id' => $tenant->id,
            'domain' => $tenant->domains->first()->domain ?? 'No domain',
            'data' => $tenant->data,
            'status' => $tenant->data['status'] ?? 'not set'
        ];
    }));
});

// Add this route with your other routes
Route::get('/debug-tenant-structure', [TenantController::class, 'debugStructure'])->name('tenants.debug-structure');

// Add this route with your other routes
Route::get('/debug-tenant-status', function() {
    $tenants = \App\Models\Tenant::all();
    
    $results = [];
    foreach ($tenants as $tenant) {
        $results[] = [
            'id' => $tenant->id,
            'created_at' => $tenant->created_at,
            'data_raw' => $tenant->getAttributes()['data'] ?? null,
            'data_parsed' => $tenant->data,
            'data_type' => gettype($tenant->data),
            'status_direct' => $tenant->data['status'] ?? 'not set',
            'subscription' => $tenant->subscription
        ];
    }
    
    return response()->json([
        'db_driver' => config('database.default'),
        'tenants_count' => count($results),
        'tenants' => $results
    ]);
});

// Add this route with your other routes
Route::get('/tenants/{id}/initialize-status', [TenantController::class, 'initializeStatus'])->name('tenants.initialize-status');

// Public domain request routes
Route::get('/request-domain', [DomainRequestController::class, 'showRequestForm'])->name('request-domain');
Route::post('/domain-requests', [DomainRequestController::class, 'store'])->name('domain-requests.store');

// Admin domain request management routes (middleware disabled for now for easier testing)



Route::post('/domain-requests/{id}/reject', [DomainRequestController::class, 'reject'])->name('domain-requests.reject');Route::post('/domain-requests/{id}/approve', [DomainRequestController::class, 'approve'])->name('domain-requests.approve');Route::get('/domain-requests', [DomainRequestController::class, 'index'])->name('domain-requests.index');
// Resource routes for tenants
Route::resource('tenants', TenantController::class);

// Add this route with your other routes
Route::get('/fix-all-tenant-data', [TenantController::class, 'fixAllTenantsData'])->name('tenants.fix-all-data');


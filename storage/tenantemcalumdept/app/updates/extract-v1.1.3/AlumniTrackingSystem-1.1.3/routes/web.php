<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DomainRequestController;
use Illuminate\Support\Facades\Route;

// Define central domain routes - fixed format for domain constraints
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->name('central.')->group(function () {
        Route::get('/', function () {
            return view('auth.login');
        });

        Route::get('/dashboard', function () {
            return view('central.dashboard');
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

// Admin domain request management routes
Route::middleware(['auth', \App\Http\Middleware\EnsureCentralAdmin::class])->group(function() {
    Route::get('/domain-requests', [DomainRequestController::class, 'index'])->name('domain-requests.index');
    Route::post('/domain-requests/{id}/approve', [DomainRequestController::class, 'approve'])->name('domain-requests.approve');
    Route::post('/domain-requests/{id}/reject', [DomainRequestController::class, 'reject'])->name('domain-requests.reject');
});

// Resource routes for tenants
Route::resource('tenants', TenantController::class)->middleware(['auth', \App\Http\Middleware\EnsureCentralAdmin::class]);

// Add this route with your other routes
Route::get('/fix-all-tenant-data', [TenantController::class, 'fixAllTenantsData'])->name('tenants.fix-all-data');

// Plans & Subscription Routes
Route::get('plans', [App\Http\Controllers\PlanController::class, 'index'])->name('plans.index');
Route::get('plans/{plan}', [App\Http\Controllers\PlanController::class, 'show'])->name('plans.show');
Route::post('plans/{plan}/subscribe', [App\Http\Controllers\PlanController::class, 'subscribe'])->name('plans.subscribe');
Route::get('subscription', [App\Http\Controllers\PlanController::class, 'currentSubscription'])->name('plans.subscription');
Route::post('subscription/cancel', [App\Http\Controllers\PlanController::class, 'cancelSubscription'])->name('plans.cancel');

// Admin Routes (can be expanded as needed)
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureCentralAdmin::class])->group(function () {
    Route::get('plans', [App\Http\Controllers\PlanController::class, 'adminIndex'])->name('plans.index');
    Route::get('plans/create', [App\Http\Controllers\PlanController::class, 'adminCreate'])->name('plans.create');
    Route::post('plans', [App\Http\Controllers\PlanController::class, 'adminStore'])->name('plans.store');
    Route::get('plans/{plan}', [App\Http\Controllers\PlanController::class, 'adminShow'])->name('plans.show');
    Route::get('plans/{plan}/edit', [App\Http\Controllers\PlanController::class, 'adminEdit'])->name('plans.edit');
    Route::put('plans/{plan}', [App\Http\Controllers\PlanController::class, 'adminUpdate'])->name('plans.update');
});

// Support Ticket Routes
Route::resource('support', App\Http\Controllers\SupportTicketController::class);
Route::post('support/{id}/response', [App\Http\Controllers\SupportTicketController::class, 'addResponse'])->name('support.response');

// Debug Routes - remove in production
Route::get('/debug-routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return $route;
    });
    
    return view('debug-routes', ['routes' => $routes]);
})->middleware(['auth']);

// System Version Management Routes (Admin Only)
Route::prefix('system')->name('system.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('versions', [App\Http\Controllers\SystemVersionController::class, 'index'])->name('versions');
    Route::get('check-updates', [App\Http\Controllers\SystemVersionController::class, 'checkForUpdates'])->name('check-updates');
    Route::post('update/{id}', [App\Http\Controllers\SystemVersionController::class, 'updateToVersion'])->name('update');
    Route::post('rollback/{id}', [App\Http\Controllers\SystemVersionController::class, 'rollback'])->name('rollback');
});


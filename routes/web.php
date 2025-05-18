<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DomainRequestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

// Debug route to activate version v1.1.0
Route::get('/debug-activate-version', function () {
    $updated = DB::table('system_versions')
        ->where('version', 'v1.1.0')
        ->update(['is_active' => true]);
    
    return "Updated {$updated} version records. Go back to version management.";
});

// Debug route to mark v1.1.0 as the current version
Route::get('/debug-set-current-version', function () {
    // First, unmark all current versions
    DB::table('system_versions')->update(['is_current' => false]);
    
    // Then mark v1.1.0 as current
    $updated = DB::table('system_versions')
        ->where('version', 'v1.1.0')
        ->update([
            'is_current' => true,
            'is_active' => true,
            'installed_at' => now()
        ]);
    
    return "Marked v1.1.0 as the current version. Updated {$updated} records. Go back to version management.";
});

// Debug route to test backup system
Route::get('/debug-test-backup', function () {
    // Make sure storage directories exist with proper permissions
    $storageApp = storage_path('app');
    $backupsDir = storage_path('app/backups');
    
    $results = [
        'storage_app_exists' => File::exists($storageApp),
        'storage_app_writable' => is_writable($storageApp),
        'backups_dir_exists' => File::exists($backupsDir),
    ];
    
    // Create backups directory if it doesn't exist
    if (!$results['backups_dir_exists']) {
        $created = \Illuminate\Support\Facades\File::makeDirectory($backupsDir, 0755, true);
        $results['backups_dir_created'] = $created;
    }
    
    $results['backups_dir_writable'] = is_writable($backupsDir);
    
    // Try to fix permissions if directory is not writable
    if (!$results['backups_dir_writable']) {
        chmod($backupsDir, 0755);
        $results['permissions_fixed'] = chmod($backupsDir, 0755);
        $results['backups_dir_writable_after_fix'] = is_writable($backupsDir);
    }
    
    // Test creating a small test file
    $testFile = $backupsDir . '/test.txt';
    $writeSuccess = file_put_contents($testFile, 'Test backup system ' . date('Y-m-d H:i:s'));
    $results['test_file_write'] = $writeSuccess !== false;
    
    if ($writeSuccess !== false) {
        $results['test_file_size'] = filesize($testFile);
        // Clean up
        \Illuminate\Support\Facades\File::delete($testFile);
    }
    
    return response()->json($results);
});

// Debug route to force skip backup during version update
Route::get('/debug-skip-backup', function () {
    if (app()->environment('local') || app()->environment('development')) {
        session(['skip_backup_for_update' => true]);
        return redirect()->route('system.versions')
            ->with('info', 'Backup will be skipped during the next update. This setting is temporary and will be cleared after the update.');
    } else {
        return redirect()->route('system.versions')
            ->with('error', 'Skip backup option is only available in development environments.');
    }
});

// Add this test mail route near your other debug routes
Route::get('/debug-test-mail', function() {
    try {
        // Display mail configuration
        $config = [
            "MAIL_MAILER" => config('mail.default'),
            "MAIL_HOST" => config('mail.mailers.smtp.host'),
            "MAIL_PORT" => config('mail.mailers.smtp.port'),
            "MAIL_USERNAME" => config('mail.mailers.smtp.username'),
            "MAIL_ENCRYPTION" => config('mail.mailers.smtp.encryption'),
            "MAIL_FROM_ADDRESS" => config('mail.from.address'),
            "MAIL_FROM_NAME" => config('mail.from.name'),
            "QUEUE_CONNECTION" => config('queue.default')
        ];
        
        // Send test email
        \Illuminate\Support\Facades\Mail::raw(
            'This is a test email from the Alumni Tracking System to verify email functionality.', 
            function($message) {
                $message->to('rvaxrevo@gmail.com')
                    ->subject('Test Email from Alumni Tracking System');
            }
        );
        
        return response()->json([
            'status' => 'success',
            'message' => 'Test email sent successfully!',
            'config' => $config
        ]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("Failed to send test email", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to send test email: ' . $e->getMessage(),
            'config' => $config ?? []
        ], 500);
    }
});

// Add this route to test the queue system
Route::get('/debug-queue-health', function() {
    return response()->json([
        'queue_connection' => config('queue.default'),
        'queue_connections' => config('queue.connections'),
        'database_jobs_count' => \DB::table('jobs')->count(),
        'database_failed_jobs_count' => \DB::table('failed_jobs')->count(),
    ]);
});


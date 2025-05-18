<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\InstructorController;
use App\Http\Middleware\InitializeTenancy;
use App\Models\TenantSettings;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        
        // Instructor Dashboard route
        Route::get('/instructor-dashboard', [App\Http\Controllers\InstructorDashboardController::class, 'index'])
            ->middleware(\App\Http\Middleware\EnsureInstructor::class)
            ->name('instructor.dashboard');
        
        // Alumni Portal routes
        Route::prefix('alumni-portal')->name('alumni.')->group(function() {
            Route::get('/dashboard', [App\Http\Controllers\AlumniDashboardController::class, 'index'])
                ->name('dashboard');
                
            // Routes that should be protected
            Route::middleware(\App\Http\Middleware\EnsureUserIsAlumni::class)->group(function() {
                Route::get('/profile', [App\Http\Controllers\AlumniDashboardController::class, 'profile'])
                    ->name('profile');
                    
                Route::put('/profile', [App\Http\Controllers\AlumniDashboardController::class, 'updateProfile'])
                    ->middleware('alumni.verified')
                    ->name('profile.update');
            });
        });
        
        // Instructor Alumni Management routes
        Route::prefix('instructor')->name('instructor.')->middleware(\App\Http\Middleware\EnsureInstructor::class)->group(function() {
            Route::get('/alumni', [App\Http\Controllers\InstructorAlumniController::class, 'index'])
                ->name('alumni.index');
                
            Route::get('/alumni/create', [App\Http\Controllers\InstructorAlumniController::class, 'create'])
                ->name('alumni.create');
                
            Route::post('/alumni', [App\Http\Controllers\InstructorAlumniController::class, 'store'])
                ->name('alumni.store');
                
            Route::get('/alumni/{id}', [App\Http\Controllers\InstructorAlumniController::class, 'show'])
                ->name('alumni.show');
                
            Route::get('/alumni/{id}/edit', [App\Http\Controllers\InstructorAlumniController::class, 'edit'])
                ->name('alumni.edit');
                
            Route::put('/alumni/{id}', [App\Http\Controllers\InstructorAlumniController::class, 'update'])
                ->name('alumni.update');
                
            Route::delete('/alumni/{id}', [App\Http\Controllers\InstructorAlumniController::class, 'destroy'])
                ->name('alumni.destroy');
                
            Route::get('/alumni-report-form', [App\Http\Controllers\InstructorAlumniController::class, 'reportForm'])
                ->name('alumni.report-form');
                
            Route::get('/alumni-report', [App\Http\Controllers\InstructorAlumniController::class, 'generateReport'])
                ->name('alumni.report');
                
            Route::get('/alumni-reports', [App\Http\Controllers\InstructorAlumniController::class, 'reports'])
                ->name('alumni.reports');
        });
        
        // Tenant Admin Alumni Management routes
        Route::prefix('alumni')->group(function() {
            Route::get('/', [App\Http\Controllers\AlumniController::class, 'index'])
                ->name('alumni.index');
                
            Route::get('/create', [App\Http\Controllers\AlumniController::class, 'create'])
                ->name('alumni.create');
                
            Route::post('/', [App\Http\Controllers\AlumniController::class, 'store'])
                ->name('alumni.store');
            
            // Put these report routes before any parameterized routes
            Route::get('/report-form', [App\Http\Controllers\AlumniController::class, 'reportForm'])
                ->name('alumni.report-form');
                  
            Route::get('/report', [App\Http\Controllers\AlumniController::class, 'generateReport'])
                ->name('alumni.report');
                
            Route::get('/reports', [App\Http\Controllers\AlumniController::class, 'reports'])
                ->name('alumni.reports');
                
            // Parameterized routes come after specific routes    
            Route::get('/{id}', [App\Http\Controllers\AlumniController::class, 'show'])
                ->where('id', '[0-9]+')
                ->name('alumni.show');
                
            Route::get('/{id}/edit', [App\Http\Controllers\AlumniController::class, 'edit'])
                ->where('id', '[0-9]+')
                ->name('alumni.edit');
                
            Route::put('/{id}', [App\Http\Controllers\AlumniController::class, 'update'])
                ->where('id', '[0-9]+')
                ->name('alumni.update');
                
            Route::delete('/{id}', [App\Http\Controllers\AlumniController::class, 'destroy'])
                ->where('id', '[0-9]+')
                ->name('alumni.destroy');
        });
        
        // Debug route for subscription - REMOVE IN PRODUCTION
        Route::get('/debug-subscription', function() {
            // Get tenant directly from database
            $tenantId = tenant('id');
            $tenantData = DB::table('tenants')->where('id', $tenantId)->first();
            
            return response()->json([
                'tenant_id' => $tenantId,
                'dashboard_subscription' => tenant()->subscription ?? null,
                'db_subscription' => $tenantData ? $tenantData->subscription : null,
                'db_subscription_decoded' => $tenantData && is_string($tenantData->subscription) 
                    ? json_decode($tenantData->subscription, true) 
                    : null
            ]);
        });
        
        // Admin routes for tenant settings
        Route::name('tenant.')->group(function () {
            // Settings routes
            Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [TenantSettingsController::class, 'update'])->name('settings.update');
            
            // Plan upgrade request route
            Route::get('/plan-upgrade/{planType}', [App\Http\Controllers\TenantPlanController::class, 'requestUpgrade'])
                ->name('plan.upgrade.request');
            
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
            
            // Instructor management routes - restrict to tenant admins
            Route::prefix('instructors')->name('instructors.')
                ->middleware([\App\Http\Middleware\CheckSubscription::class . ':instructors', \App\Http\Middleware\EnsureTenantAdmin::class])
                ->group(function () {
                    Route::get('/', [InstructorController::class, 'index'])->name('index');
                    Route::get('/create', [InstructorController::class, 'create'])->name('create');
                    Route::post('/', [InstructorController::class, 'store'])->name('store');
                    Route::get('/{id}', [InstructorController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [InstructorController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [InstructorController::class, 'update'])->name('update');
                    Route::delete('/{id}', [InstructorController::class, 'destroy'])->name('destroy');
                });
        });

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

    Route::get('/debug-user', function() {
        $user = Auth::user();
        $alumni = $user->alumni;
        
        return response()->json([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'alumni' => $alumni ? [
                'id' => $alumni->id,
                'first_name' => $alumni->first_name,
                'last_name' => $alumni->last_name,
                'email' => $alumni->email,
                'is_verified' => $alumni->is_verified,
                'user_id' => $alumni->user_id
            ] : null,
            'is_role_alumni' => $user->role === \App\Models\User::ROLE_ALUMNI
        ]);
    });

    // System Version Management Routes (Admin Only)
    Route::prefix('system')->name('system.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('versions', [App\Http\Controllers\SystemVersionController::class, 'index'])->name('versions');
        Route::get('check-updates', [App\Http\Controllers\SystemVersionController::class, 'checkForUpdates'])->name('check-updates');
        Route::post('update/{id}', [App\Http\Controllers\SystemVersionController::class, 'updateToVersion'])->name('update');
        Route::post('rollback/{id}', [App\Http\Controllers\SystemVersionController::class, 'rollback'])->name('rollback');
    });

    // Support Ticket Routes
    Route::resource('support', \App\Http\Controllers\SupportTicketController::class);
    Route::post('support/{id}/response', [\App\Http\Controllers\SupportTicketController::class, 'addResponse'])->name('support.response');

    // Debug Routes - remove in production
    Route::get('/debug-routes', function () {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route;
        });
        
        return view('debug-routes', ['routes' => $routes]);
    })->middleware(['auth']);
});

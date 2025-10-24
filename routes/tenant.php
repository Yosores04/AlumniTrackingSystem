<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\TestController;
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
        
        // Password change routes - available to all authenticated users
        Route::get('/change-password', [App\Http\Controllers\PasswordController::class, 'show'])->name('password.show');
        Route::post('/change-password', [App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');
        
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
                    ->middleware(['auth', 'alumni.verified'])
                    ->name('profile.update');
            });
        });

        Route::get('/test', [App\Http\Controllers\TestController::class, 'index']);

        // Instructor Alumni Management routes (redirected to unified system)
        Route::prefix('instructor')->name('instructor.')->middleware(\App\Http\Middleware\EnsureInstructor::class)->group(function() {
            // Redirect instructor routes to unified alumni routes
            Route::get('/alumni', function() {
                return redirect()->route('alumni.index');
            })->name('alumni.index');
                
            Route::get('/alumni/create', function() {
                return redirect()->route('alumni.create');
            })->name('alumni.create');
                
            Route::post('/alumni', function() {
                return redirect()->route('alumni.store');
            })->name('alumni.store');
                
            Route::get('/alumni/{id}', function($id) {
                return redirect()->route('alumni.show', $id);
            })->name('alumni.show');
                
            Route::get('/alumni/{id}/edit', function($id) {
                return redirect()->route('alumni.edit', $id);
            })->name('alumni.edit');
                
            Route::put('/alumni/{id}', function($id) {
                return redirect()->route('alumni.update', $id);
            })->name('alumni.update');
                
            Route::delete('/alumni/{id}', function($id) {
                return redirect()->route('alumni.destroy', $id);
            })->name('alumni.destroy');
                
            Route::get('/alumni-report-form', function() {
                return redirect()->route('alumni.report-form');
            })->name('alumni.report-form');
                
            Route::get('/alumni-report', function() {
                return redirect()->route('alumni.report');
            })->name('alumni.report');
                
            Route::get('/alumni-reports', function() {
                return redirect()->route('alumni.reports');
            })->name('alumni.reports');

            // Employment History redirects
            Route::get('/alumni/{alumni}/employment-history', function($alumni) {
                return redirect()->route('alumni.employment-history.index', $alumni);
            })->name('alumni.employment-history.index');
            
            Route::get('/alumni/{alumni}/employment-history/create', function($alumni) {
                return redirect()->route('alumni.employment-history.create', $alumni);
            })->name('alumni.employment-history.create');
        });
        
        // Unified Alumni Management routes (for both tenant admin and instructors)
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
                
            // Employment History nested routes
            Route::get('/{alumni}/employment-history', [App\Http\Controllers\EmploymentHistoryController::class, 'index'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.employment-history.index');
                
            Route::get('/{alumni}/employment-history/create', [App\Http\Controllers\EmploymentHistoryController::class, 'create'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.employment-history.create');
                
            Route::post('/{alumni}/employment-history', [App\Http\Controllers\EmploymentHistoryController::class, 'store'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.employment-history.store');
                
            Route::get('/{alumni}/employment-history/{employmentHistory}', [App\Http\Controllers\EmploymentHistoryController::class, 'show'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.employment-history.show');
                
            Route::get('/{alumni}/employment-history/{employmentHistory}/edit', [App\Http\Controllers\EmploymentHistoryController::class, 'edit'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.employment-history.edit');
                
            Route::put('/{alumni}/employment-history/{employmentHistory}', [App\Http\Controllers\EmploymentHistoryController::class, 'update'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.employment-history.update');
                
            Route::delete('/{alumni}/employment-history/{employmentHistory}', [App\Http\Controllers\EmploymentHistoryController::class, 'destroy'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.employment-history.destroy');
                
            // Instructor Notes nested routes
            Route::get('/{alumni}/instructor-notes', [App\Http\Controllers\InstructorNoteController::class, 'index'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.instructor-notes.index');
                
            Route::get('/{alumni}/instructor-notes/create', [App\Http\Controllers\InstructorNoteController::class, 'create'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.instructor-notes.create')
                ->middleware(\App\Http\Middleware\EnsureInstructor::class);
                
            Route::post('/{alumni}/instructor-notes', [App\Http\Controllers\InstructorNoteController::class, 'store'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.instructor-notes.store')
                ->middleware(\App\Http\Middleware\EnsureInstructor::class);
                
            Route::get('/{alumni}/instructor-notes/{instructorNote}', [App\Http\Controllers\InstructorNoteController::class, 'show'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.instructor-notes.show');
                
            Route::get('/{alumni}/instructor-notes/{instructorNote}/edit', [App\Http\Controllers\InstructorNoteController::class, 'edit'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.instructor-notes.edit')
                ->middleware(\App\Http\Middleware\EnsureInstructor::class);
                
            Route::put('/{alumni}/instructor-notes/{instructorNote}', [App\Http\Controllers\InstructorNoteController::class, 'update'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.instructor-notes.update')
                ->middleware(\App\Http\Middleware\EnsureInstructor::class);
                
            Route::delete('/{alumni}/instructor-notes/{instructorNote}', [App\Http\Controllers\InstructorNoteController::class, 'destroy'])
                ->where('alumni', '[0-9]+')
                ->name('alumni.instructor-notes.destroy')
                ->middleware(\App\Http\Middleware\EnsureInstructor::class);
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
            
            // Profile routes
            Route::get('/profile', [App\Http\Controllers\TenantProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [App\Http\Controllers\TenantProfileController::class, 'update'])->name('profile.update');
            Route::get('/profile/show', [App\Http\Controllers\TenantProfileController::class, 'show'])->name('profile.show');
            
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

    // Alumni Related Resource Routes
    Route::resource('alumni.social-accounts', \App\Http\Controllers\SocialAccountController::class);
    Route::resource('alumni.attachments', \App\Http\Controllers\AttachmentController::class);
    Route::resource('alumni.tracking-statuses', \App\Http\Controllers\TrackingStatusController::class);
    
    // Additional Social Account Routes
    Route::post('alumni/{alumni}/social-accounts/{socialAccount}/verify', [\App\Http\Controllers\SocialAccountController::class, 'toggleVerification'])->name('alumni.social-accounts.verify');
    Route::post('alumni/{alumni}/social-accounts/{socialAccount}/visibility', [\App\Http\Controllers\SocialAccountController::class, 'toggleVisibility'])->name('alumni.social-accounts.visibility');
    
    // Additional Attachment Routes
    Route::get('alumni/{alumni}/attachments/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('alumni.attachments.download');
    Route::post('alumni/{alumni}/attachments/{attachment}/verify', [\App\Http\Controllers\AttachmentController::class, 'toggleVerification'])->name('alumni.attachments.verify');
    Route::post('alumni/{alumni}/attachments/{attachment}/visibility', [\App\Http\Controllers\AttachmentController::class, 'toggleVisibility'])->name('alumni.attachments.visibility');
    
    // Additional Tracking Status Routes
    Route::post('alumni/{alumni}/tracking-statuses/{trackingStatus}/set-current', [\App\Http\Controllers\TrackingStatusController::class, 'setCurrent'])->name('alumni.tracking-statuses.set-current');
    Route::get('tracking-statuses/by-status', [\App\Http\Controllers\TrackingStatusController::class, 'byStatusType'])->name('tracking-statuses.by-status');
    Route::get('tracking-statuses/statistics', [\App\Http\Controllers\TrackingStatusController::class, 'statistics'])->name('tracking-statuses.statistics');
    Route::get('tracking-statuses/follow-ups', [\App\Http\Controllers\TrackingStatusController::class, 'followUps'])->name('tracking-statuses.follow-ups');

    // General Attachment Routes (for all users - students, instructors, etc.)
    Route::prefix('attachments')->name('attachments.')->middleware('auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\GeneralAttachmentController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\GeneralAttachmentController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\GeneralAttachmentController::class, 'store'])->name('store');
        Route::get('/public', [\App\Http\Controllers\GeneralAttachmentController::class, 'publicIndex'])->name('public');
        Route::get('/category/{category}', [\App\Http\Controllers\GeneralAttachmentController::class, 'category'])->name('category');
        Route::get('/{attachment}', [\App\Http\Controllers\GeneralAttachmentController::class, 'show'])->name('show');
        Route::get('/{attachment}/edit', [\App\Http\Controllers\GeneralAttachmentController::class, 'edit'])->name('edit');
        Route::put('/{attachment}', [\App\Http\Controllers\GeneralAttachmentController::class, 'update'])->name('update');
        Route::delete('/{attachment}', [\App\Http\Controllers\GeneralAttachmentController::class, 'destroy'])->name('destroy');
        Route::get('/{attachment}/download', [\App\Http\Controllers\GeneralAttachmentController::class, 'download'])->name('download');
        Route::post('/{attachment}/verify', [\App\Http\Controllers\GeneralAttachmentController::class, 'toggleVerification'])->name('verify');
    });

    // Debug Routes - remove in production
    Route::get('/debug-routes', function () {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route;
        });
        
        return view('debug-routes', ['routes' => $routes]);
    })->middleware(['auth']);
});

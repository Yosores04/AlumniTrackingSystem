<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Define central domain routes - fixed format for domain constraints
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return view('welcome');
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


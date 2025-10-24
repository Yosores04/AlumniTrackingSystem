<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TenantSettings;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\App;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the central domain login view (for administrators).
     */
    public function centralLogin(): View
    {
        return view('central.auth.login');
    }

    /**
     * Handle central domain authentication request.
     * Only allows users with central_admin role.
     */
    public function centralStore(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Check if authenticated user is a central admin
        $user = Auth::user();
        
        if ($user->role !== User::ROLE_CENTRAL_ADMIN) {
            // Log out non-admin users
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'Access denied. Only central administrators can log in to this portal. Please use your campus portal to access your account.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        
        return redirect()->intended(route('central.dashboard'))
            ->with('success', 'Welcome back, ' . $user->name . '! You have successfully signed in.');
    }

    /**
     * Display the tenant domain login view (for alumni/instructors).
     */
    public function tenantLogin(): View
    {
        $settings = null;
        
        if (function_exists('tenant') && tenant()) {
            try {
                $settings = TenantSettings::getSettings();
            } catch (\Exception $e) {
                // Fallback to null
            }
        }
        
        if (!$settings) {
            $settings = new \stdClass();
            $settings->logo_path = null;
            $settings->logo_url = null;
        }
        
        return view('tenant.auth.login', compact('settings'));
    }

    /**
     * Handle tenant domain authentication request.
     */
    public function tenantStore(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        
        if ($user->role === \App\Models\User::ROLE_INSTRUCTOR) {
            return redirect()->intended(route('instructor.dashboard', absolute: false))
                ->with('success', 'Welcome back, ' . $user->name . '! You have successfully signed in.');
        }
        
        if ($user->role === \App\Models\User::ROLE_ALUMNI) {
            return redirect()->route('alumni.dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '! You have successfully signed in.');
        }
        
        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Welcome back, ' . $user->name . '! You have successfully signed in.');
    }

    /**
     * Display the login view (determines central vs tenant automatically).
     */
    public function create(): View
    {
        // Determine if we're on central or tenant domain
        $isCentral = in_array(request()->getHost(), config('tenancy.central_domains'));
        
        if ($isCentral) {
            return $this->centralLogin();
        }
        
        return $this->tenantLogin();
    }

    /**
     * Handle an incoming authentication request (determines central vs tenant automatically).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Determine if we're on central or tenant domain
        $isCentral = in_array(request()->getHost(), config('tenancy.central_domains'));
        
        if ($isCentral) {
            return $this->centralStore($request);
        }
        
        return $this->tenantStore($request);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TenantSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\App;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $settings = null;
        
        // Only try to get tenant settings if we're in a tenant context
        if (function_exists('tenant') && tenant()) {
            try {
                $settings = TenantSettings::getSettings();
            } catch (\Exception $e) {
                // Fallback to null if settings can't be retrieved
                // This could happen if the tenant database isn't set up yet
            }
        }
        
        // If no settings were found (central domain or new tenant), create a default object
        if (!$settings) {
            $settings = new \stdClass();
            $settings->background_image_path = null;
            $settings->background_image_url = null;
            $settings->logo_path = null;
            $settings->logo_url = null;
            $settings->site_name = 'Alumni Tracking System';
        }
        
        return view('auth.login', compact('settings'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        
        if ($user->role === \App\Models\User::ROLE_INSTRUCTOR) {
            return redirect()->intended(route('instructor.dashboard', absolute: false));
        }
        
        if ($user->role === \App\Models\User::ROLE_ALUMNI) {
            return redirect()->route('alumni.dashboard');
        }
        
        return redirect()->intended(route('dashboard', absolute: false));
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

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckAlumniVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip verification check for support/ticket routes
        if ($request->routeIs('support.*')) {
            return $next($request);
        }

        // Get the authenticated user and check if they are a verified alumni
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }

        // Ensure user has alumni relationship
        if (!$user->alumni) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an alumni profile. Please contact support for assistance.');
        }
        
        // Check if alumni is verified
        if (!$user->alumni->is_verified) {
            // If the request is for profile update, prevent it with a message
            if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
                return redirect()->route('alumni.profile')
                    ->with('error', 'Your account is pending verification. You cannot update your profile at this time. Please contact support for assistance.');
            }
            
            // If viewing profile page, we'll allow it but the controller will handle restricting the form
            if ($request->routeIs('alumni.profile')) {
                return $next($request);
            }
            
            // Redirect to the support page for other restricted actions
            return redirect()->route('support.index')
                ->with('warning', 'Your account is pending verification. Some features are restricted until your account is verified by an administrator.');
        }

        return $next($request);
    }
} 
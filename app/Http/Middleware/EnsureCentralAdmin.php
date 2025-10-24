<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EnsureCentralAdmin
{
    /**
     * Handle an incoming request.
     * Ensures only users with ROLE_CENTRAL_ADMIN can access central domain authenticated routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('central.login.form');
        }

        $user = Auth::user();

        // Only allow central admins
        if ($user->role !== User::ROLE_CENTRAL_ADMIN) {
            // Log out non-admin users attempting to access central domain
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('central.login.form')
                ->withErrors([
                    'email' => 'Access denied. Only central administrators can access this portal. Please use your campus portal for access.',
                ]);
        }

        return $next($request);
    }
} 
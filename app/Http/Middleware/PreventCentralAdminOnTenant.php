<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PreventCentralAdminOnTenant
{
    /**
     * Handle an incoming request.
     * Prevents central admins from accessing tenant domains.
     * Central admins should only work on the central domain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Prevent central admins from accessing tenant domains
            if ($user->role === User::ROLE_CENTRAL_ADMIN) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Get the central domain
                $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
                $centralUrl = (request()->secure() ? 'https://' : 'http://') . $centralDomain;

                return redirect($centralUrl . '/login')
                    ->withErrors([
                        'email' => 'Central administrators must use the main portal. You have been redirected.',
                    ]);
            }
        }

        return $next($request);
    }
}

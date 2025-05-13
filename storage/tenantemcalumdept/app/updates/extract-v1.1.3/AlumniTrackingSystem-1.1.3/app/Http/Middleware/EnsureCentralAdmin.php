<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureCentralAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user() || auth()->user()->role !== User::ROLE_CENTRAL_ADMIN) {
            abort(403, 'Unauthorized action. Only central administrators can access this page.');
        }

        return $next($request);
    }
} 
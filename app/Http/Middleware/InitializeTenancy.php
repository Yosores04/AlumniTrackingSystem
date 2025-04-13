<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class InitializeTenancy extends InitializeTenancyByDomain
{
    /**
     * Handle an incoming request.
     *
     * @param mixed $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Log the incoming request for debugging
        Log::info('Tenant request', [
            'domain' => $request->getHost(),
            'uri' => $request->getRequestUri(),
            'is_tenant_domain' => !in_array($request->getHost(), config('tenancy.central_domains')),
        ]);

        return parent::handle($request, $next);
    }
}

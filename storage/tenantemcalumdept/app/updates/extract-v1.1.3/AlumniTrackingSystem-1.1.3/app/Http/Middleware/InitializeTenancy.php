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
        ]);

        // Check if current domain is a tenant domain before initialization
        if (!in_array($request->getHost(), config('tenancy.central_domains'))) {
            $domain = $request->getHost();
            $tenantDomain = \Stancl\Tenancy\Database\Models\Domain::where('domain', $domain)->first();
            
            if ($tenantDomain) {
                $tenant = \App\Models\Tenant::find($tenantDomain->tenant_id);
                
                // Debug logging
                Log::info('Checking tenant status', [
                    'tenant_id' => $tenant->id ?? 'none',
                    'status' => $tenant->status ?? 'not set',
                    'data' => $tenant->data ?? null,
                ]);
                
                if ($tenant) {
                    // Check the dedicated status column rather than data JSON
                    $status = $tenant->status;
                    
                    if ($status !== 'active') {
                        // Tenant is suspended, show suspended message
                        tenancy()->initialize($tenant);
                        $data = $tenant->data ?: [];
                        $subscription = $tenant->subscription ?? [];
                        $plan = $subscription['plan'] ?? 'free';
                        
                        return response()->view('central.suspended', [
                            'tenant' => $tenant,
                            'status' => $status,
                            'reason' => $data['suspension_reason'] ?? 'This account has been suspended.',
                            'suspended_at' => isset($data['suspended_at']) ? \Carbon\Carbon::parse($data['suspended_at'])->format('F j, Y') : null,
                            'plan' => $plan
                        ], 403);
                    }
                }
            }
        }

        return parent::handle($request, $next);
    }
}

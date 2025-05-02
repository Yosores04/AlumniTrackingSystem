<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        $tenant = tenant();
        
        // Check if tenant has an active subscription
        if (!method_exists($tenant, 'hasActiveSubscription') || !$tenant->hasActiveSubscription()) {
            return redirect()->route('plans.index')
                ->with('error', 'Your subscription has expired. Please renew your subscription to continue using this feature.');
        }
        
        // If no specific feature is requested, just check for active subscription
        if (!$feature) {
            return $next($request);
        }
        
        // Check if the tenant's plan includes the requested feature
        $plan = $tenant->plan;
        
        // If plan is null, we can't check features
        if (!$plan) {
            return $this->featureNotAvailable();
        }
        
        switch ($feature) {
            case 'custom_fields':
                if (!$plan->has_custom_fields) {
                    return $this->featureNotAvailable();
                }
                break;
                
            case 'advanced_analytics':
                if (!$plan->has_advanced_analytics) {
                    return $this->featureNotAvailable();
                }
                break;
                
            case 'integrations':
                if (!$plan->has_integrations) {
                    return $this->featureNotAvailable();
                }
                break;
                
            case 'job_board':
                if (!$plan->has_job_board) {
                    return $this->featureNotAvailable();
                }
                break;
                
            case 'custom_branding':
                if (!$plan->has_custom_branding) {
                    return $this->featureNotAvailable();
                }
                break;
                
            case 'instructors':
                if (method_exists($tenant, 'hasReachedInstructorLimit') && $tenant->hasReachedInstructorLimit()) {
                    return redirect()->route('tenant.instructors.index')
                        ->with('error', 'You have reached the maximum number of instructors allowed in your plan. Please upgrade to add more instructors.');
                }
                break;
                
            case 'alumni':
                if (method_exists($tenant, 'hasReachedAlumniLimit') && $tenant->hasReachedAlumniLimit()) {
                    return redirect()->route('tenant.dashboard')
                        ->with('error', 'You have reached the maximum number of alumni records allowed in your plan. Please upgrade to add more alumni.');
                }
                break;
        }
        
        return $next($request);
    }
    
    /**
     * Return a response for when a feature is not available.
     */
    private function featureNotAvailable(): Response
    {
        return redirect()->route('plans.index')
            ->with('error', 'This feature is not available in your current plan. Please upgrade to access this feature.');
    }
} 
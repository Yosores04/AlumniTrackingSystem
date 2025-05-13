<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlanUpgradeRequest;
use Illuminate\Support\Facades\Log;

class TenantPlanController extends Controller
{
    /**
     * Request a plan upgrade for the current tenant
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $planType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestUpgrade(Request $request, $planType)
    {
        try {
            // Validate plan type
            if (!in_array($planType, ['basic', 'premium'])) {
                return redirect()->back()->with('error', 'Invalid plan type specified.');
            }
            
            // Get tenant information
            $tenant = tenant();
            $tenantName = $tenant->name ?? $tenant->id;
            $tenantId = $tenant->id;
            $domain = $request->getHost();
            $currentPlan = $tenant->plan->name ?? 'Free';
            
            // Get admin contact from configuration
            $adminEmail = config('app.admin_email', 'admin@example.com');
            
            // Get additional request details
            $requesterName = auth()->user()->name;
            $requesterEmail = auth()->user()->email;
            // Set default request details for GET requests
            if ($request->isMethod('get')) {
                $requestDetails = $planType === 'basic' 
                    ? 'Requesting upgrade to Basic plan for color customization features.' 
                    : 'Requesting upgrade to Premium plan for advanced customization features.';
            } else {
                $requestDetails = $request->input('request_details', '');
            }
            
            // Create data array for email
            $data = [
                'tenant_name' => $tenantName,
                'tenant_id' => $tenantId,
                'domain' => $domain,
                'current_plan' => $currentPlan,
                'requested_plan' => ucfirst($planType),
                'requester_name' => $requesterName,
                'requester_email' => $requesterEmail,
                'request_details' => $requestDetails,
                'request_time' => now()->format('Y-m-d H:i:s')
            ];
            
            // Send email notification to admin
            Mail::to($adminEmail)->send(new PlanUpgradeRequest($data));
            
            // Log the request
            Log::info('Plan upgrade request submitted', $data);
            
            // Return success response
            return redirect()->back()->with('success', 'Your plan upgrade request has been submitted. Our team will contact you shortly.');
        } catch (\Exception $e) {
            Log::error('Error processing plan upgrade request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'We encountered an error processing your request. Please try again later.');
        }
    }
} 
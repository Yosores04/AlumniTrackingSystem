<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Notifications\TenantCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    /**
     * Display the tenant creation form.
     */
    public function create()
    {
        // Fetch all tenants and eager load their domains to reduce database queries
        $tenants = \App\Models\Tenant::with('domains')->latest()->paginate(10);
        
        // Pass tenants to the view - use central namespace instead of tenants
        return view('central.create', compact('tenants'));
    }

    /**
     * Store a newly created tenant.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domain_prefix' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9\-]+$/i',
                Rule::unique('domains', 'domain')->where(function ($query) use ($request) {
                    return $query->where('domain', $request->domain_prefix . '.localhost');
                }),
            ],
        ], [
            'domain_prefix.regex' => 'The domain prefix may only contain letters, numbers, and hyphens.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tenants.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Generate a random password
        $password = Str::random(12);
        
        // Generate tenant ID from domain prefix
        $tenantId = $request->domain_prefix;
        
        // Create FULL domain with .localhost
        $domain = $request->domain_prefix . '.localhost';

        try {
            // Create the tenant
            $tenant = Tenant::create([
                'id' => $tenantId,
                'status' => 'active',
                'data' => [] // Default empty data object
            ]);
            $tenant->domains()->create(['domain' => $domain]);

            // Assign default "Free" plan to new tenant
            try {
                $freePlan = \App\Models\Plan::where('slug', 'free')->first();
                if ($freePlan) {
                    $tenant->plan_id = $freePlan->id;
                    $tenant->billing_cycle = 'monthly';
                    $tenant->plan_expires_at = now()->addYear(); // Give them a full year on free plan
                    $tenant->save();
                    
                    Log::info('Assigned free plan to new tenant', [
                        'tenant_id' => $tenant->id,
                        'plan_id' => $freePlan->id
                    ]);
                } else {
                    Log::warning('Free plan not found when creating tenant', [
                        'tenant_id' => $tenant->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error assigning free plan to tenant', [
                    'tenant_id' => $tenant->id,
                    'error' => $e->getMessage()
                ]);
                // Continue with tenant creation even if plan assignment fails
            }

            // Run migrations and create admin user
            tenancy()->initialize($tenant);
            
            // Create admin user for the tenant
            \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($password),
                'role' => \App\Models\User::ROLE_TENANT_ADMIN,
            ]);

            // Return to central context
            tenancy()->end();

            // Prepare tenant info for notification
            $tenantInfo = [
                'id' => $tenantId,
                'domain' => $domain,
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password
            ];

            // Send email notification
            Notification::route('mail', $request->email)
                ->notify(new TenantCreated($tenantInfo));

            return redirect()->route('tenants.create')
                ->with('success', "Tenant created successfully! An email with login details has been sent to {$request->email}")
                ->with('tenant_info', $tenantInfo);
                
        } catch (\Exception $e) {
            return redirect()->route('tenants.create')
                ->with('error', "Error creating tenant: " . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified tenant.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $tenant = Tenant::with('domains')->findOrFail($id);
            
            // Safely extract domain
            $domainText = 'No domain';
            if ($tenant->domains && $tenant->domains->count() > 0) {
                $domainObj = $tenant->domains->first();
                if ($domainObj && isset($domainObj->domain)) {
                    $domainText = $domainObj->domain;
                }
            }
            
            // Debug info
            \Illuminate\Support\Facades\Log::info('Tenant domain info', [
                'tenant_id' => $tenant->id,
                'domains_relationship' => $tenant->domains,
                'first_domain' => $tenant->domains->first(),
                'domain_text' => $domainText
            ]);
            
            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('info', "Tenant ID: {$tenant->id}, Domain: {$domainText}");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in show method', [
                'tenant_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('error', "Error retrieving tenant information: " . $e->getMessage());
        }
    }

    /**
     * Update the specified tenant.
     */
    public function update(Request $request, $id)
    {
        Log::info('Updating tenant', [
            'tenant_id' => $id,
            'request_data' => $request->all()
        ]);
        
        $tenant = Tenant::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'domain_prefix' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9\-]+$/i',
                Rule::unique('domains', 'domain')->where(function ($query) use ($request, $tenant) {
                    return $query->where('domain', $request->domain_prefix . '.localhost')
                        ->whereNotIn('tenant_id', [$tenant->id]);
                }),
            ],
            'status' => 'required|in:active,inactive,suspended',
            'subscription_plan' => 'nullable|in:free,basic,premium',
            'suspension_reason' => 'nullable|required_if:status,suspended|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update domain if changed
            $newDomain = $request->domain_prefix . '.localhost';
            $currentDomain = $tenant->domains->first()->domain ?? null;
            
            if ($currentDomain && $currentDomain !== $newDomain) {
                // Update the domain
                $tenant->domains()->delete(); // Remove old domain
                $tenant->domains()->create(['domain' => $newDomain]); // Create new domain
            }
            
            // Update tenant status
            $statusChanged = $tenant->status !== $request->status;
            $oldStatus = $tenant->status;
            
            // Set the new status
            $tenant->status = $request->status;
            
            // Additional metadata in data column
            $data = $tenant->data ?? [];
            
            // If status is being changed to suspended, record suspension information
            if ($statusChanged && $request->status === 'suspended') {
                $data['suspended_at'] = now()->toDateTimeString();
                $data['suspension_reason'] = $request->suspension_reason;
            }
            
            // If reactivating a suspended tenant, clear suspension info
            if ($statusChanged && $request->status === 'active' && $oldStatus === 'suspended') {
                $data['reactivated_at'] = now()->toDateTimeString();
                $data['is_suspended'] = false;
            }
            
            // Set updated data back to tenant
            $tenant->data = $data;
            
            // Update subscription if provided
            if ($request->filled('subscription_plan')) {
                Log::info('Updating subscription plan', ['plan' => $request->subscription_plan]);
                
                // Get plan ID from the database based on slug
                $plan = \App\Models\Plan::where('slug', $request->subscription_plan)->first();
                
                if ($plan) {
                    // Update the plan_id in the tenant record
                    $tenant->plan_id = $plan->id;
                    $tenant->billing_cycle = 'monthly'; // Default to monthly
                    $tenant->plan_expires_at = now()->addMonth(); // Default expiration in 1 month
                    
                    // Update the JSON subscription field for backward compatibility
                    $tenant->subscription = [
                        'plan' => $request->subscription_plan,
                        'updated_at' => now()->toDateTimeString(),
                        'billing_period_end' => now()->addMonth()->toDateTimeString()
                    ];
                    
                    Log::info('Plan found and assigned', [
                        'plan_id' => $plan->id,
                        'plan_name' => $plan->name,
                        'subscription' => $tenant->subscription
                    ]);
                } else {
                    Log::error('Plan not found', ['requested_plan' => $request->subscription_plan]);
                }
            }
            
            // Debug logging
            Log::info('Saving tenant with data', [
                'tenant_id' => $tenant->id,
                'old_status' => $oldStatus,
                'new_status' => $tenant->status,
                'status_changed' => $statusChanged,
                'data' => $data,
                'subscription' => $tenant->subscription ?? null,
                'plan_id' => $tenant->plan_id
            ]);
            
            // Save tenant
            $tenant->save();
            
            Log::info('Tenant updated successfully');

            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('success', 'Tenant status and settings updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update tenant', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error updating tenant: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified tenant.
     */
    public function destroy($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            $domainName = $tenant->domains->first()->domain ?? 'unknown';
            
            // Log the deletion attempt
            Log::info('Deleting tenant', [
                'tenant_id' => $id,
                'domain' => $domainName
            ]);
            
            // Delete tenant (this will trigger cascade deletion of domains)
            $tenant->delete();
            
            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('success', "Tenant '{$id}' with domain '{$domainName}' has been deleted successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to delete tenant', [
                'tenant_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('error', "Error deleting tenant: " . $e->getMessage());
        }
    }
    
    /**
     * Change tenant subscription
     */
    public function updateSubscription(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'plan' => 'required|in:free,basic,premium',
            'billing_alignment' => 'nullable|in:immediate,end_of_period',
            'admin_message' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $tenant = Tenant::findOrFail($id);
            
            // Get existing subscription or initialize array
            $subscription = $tenant->subscription ?? [];
            $previousPlan = $subscription['plan'] ?? 'Free';
            
            // Handle billing alignment
            $billingAlignment = $request->billing_alignment ?? 'end_of_period';
            $currentPeriodEnd = $subscription['billing_period_end'] ?? now()->addMonth()->toDateTimeString();
            
            // If immediate alignment, set new period from now
            if ($billingAlignment === 'immediate') {
                $currentPeriodEnd = now()->addMonth()->toDateTimeString();
            }
            
            // Get plan ID from the database based on slug
            $plan = \App\Models\Plan::where('slug', $request->plan)->first();
            
            if ($plan) {
                // Update the plan_id in the tenant record
                $tenant->plan_id = $plan->id;
                $tenant->billing_cycle = 'monthly'; // Default to monthly
                $tenant->plan_expires_at = now()->addMonth(); // Default expiration in 1 month
                
                Log::info('Plan found and assigned in subscription update', [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name
                ]);
            } else {
                Log::error('Plan not found in subscription update', ['requested_plan' => $request->plan]);
            }
            
            // Update subscription json field for backward compatibility
            $subscription = [
                'plan' => $request->plan,
                'updated_at' => now()->toDateTimeString(),
                'billing_period_end' => $currentPeriodEnd,
                // Add any additional billing info here
            ];
            
            $tenant->subscription = $subscription;
            $tenant->save();
            
            Log::info('Tenant subscription updated', [
                'tenant_id' => $tenant->id, 
                'plan' => $request->plan,
                'plan_id' => $tenant->plan_id,
                'subscription' => $subscription
            ]);
            
            // Send notification email to tenant admin users
            try {
                // Initialize tenant connection to access its database
                tenancy()->initialize($tenant);
                
                // Get all tenant admin users
                $adminUsers = \App\Models\User::where('role', \App\Models\User::ROLE_TENANT_ADMIN)->get();
                
                if ($adminUsers->count() > 0) {
                    // Get the first domain for this tenant
                    $domain = $tenant->domains->first()->domain ?? $tenant->id . '.localhost';
                    
                    // Prepare data for email
                    $emailData = [
                        'previous_plan' => ucfirst($previousPlan),
                        'new_plan' => ucfirst($request->plan),
                        'effective_date' => now()->format('Y-m-d H:i:s'),
                        'admin_message' => $request->admin_message,
                        'login_url' => 'http://' . $domain . '/settings'
                    ];
                    
                    // Send emails to all admin users
                    foreach ($adminUsers as $adminUser) {
                        \Illuminate\Support\Facades\Mail::to($adminUser->email)
                            ->send(new \App\Mail\PlanUpdateNotification($emailData));
                        
                        Log::info('Plan update notification sent', [
                            'tenant_id' => $tenant->id,
                            'admin_email' => $adminUser->email
                        ]);
                    }
                } else {
                    Log::warning('No tenant admin users found to notify about plan update', [
                        'tenant_id' => $tenant->id
                    ]);
                }
                
                // End tenant connection
                tenancy()->end();
            } catch (\Exception $e) {
                Log::error('Failed to send plan update notification', [
                    'tenant_id' => $tenant->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Continue execution - don't fail the request just because notification failed
            }
            
            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('success', "Tenant subscription updated to {$request->plan} plan" . 
                    ($adminUsers->count() > 0 ? " and notification emails sent to tenant admins" : ""));
                
        } catch (\Exception $e) {
            Log::error('Failed to update tenant subscription', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error updating tenant subscription: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Debug tenant database structure
     */
    public function debugStructure()
    {
        // Get table columns
        $columns = DB::select('SHOW COLUMNS FROM tenants');
        
        // Get all tenants
        $tenants = DB::table('tenants')->get();
        
        // Extract status for each tenant
        $status = [];
        foreach ($tenants as $tenant) {
            if (!empty($tenant->data)) {
                $data = json_decode($tenant->data, true);
                $status[$tenant->id] = $data['status'] ?? 'not set';
            }
        }
        
        return view('central.debug', compact('columns', 'tenants', 'status'));
    }

    /**
     * Initialize or fix tenant status
     */
    public function initializeStatus($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            
            // Handle data to status migration if needed
            $data = $tenant->data ?? [];
            
            // If status in data but not in column, migrate it
            if (isset($data['status']) && $tenant->status === null) {
                $tenant->status = $data['status'];
            } 
            // If status not in data, set to active
            else if (!isset($data['status'])) {
                $tenant->status = 'active';
            }
            
            // Update tenant
            $tenant->save();
            
            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('success', "Tenant status initialized successfully. Current status: {$tenant->status}");
                
        } catch (\Exception $e) {
            return redirect()->route('tenants.create', ['tab' => 'list'])
                ->with('error', "Error initializing tenant status: " . $e->getMessage());
        }
    }

    /**
     * Directly fix all tenants data from the database level
     */
    public function fixAllTenantsData()
    {
        // Use direct database queries to avoid model casting issues
        $tenants = DB::table('tenants')->get();
        $results = [];
        
        foreach ($tenants as $tenant) {
            // Record the before state
            $before = [
                'id' => $tenant->id,
                'data_before' => $tenant->data,
                'data_type_before' => gettype($tenant->data),
                'plan_id_before' => $tenant->plan_id
            ];
            
            // Fix data JSON structure
            $data = json_decode($tenant->data, true) ?: [];
            if (!isset($data['status'])) {
                $data['status'] = 'active';
            }
            
            // Check if plan_id exists in data and needs to be moved to the plan_id column
            $planIdChanged = false;
            if (isset($data['plan_id']) && ($tenant->plan_id === null || $tenant->plan_id === 0)) {
                // Move plan_id from data to the dedicated column
                $planId = (int)$data['plan_id'];
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['plan_id' => $planId]);
                
                // Remove plan_id from data to avoid duplication
                unset($data['plan_id']);
                $planIdChanged = true;
            }
            
            // Update data column if modified
            $newData = json_encode($data);
            if ($newData !== $tenant->data) {
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['data' => $newData]);
            }
                
            // Get the tenant again to confirm changes
            $updatedTenant = DB::table('tenants')->where('id', $tenant->id)->first();
            
            // Record the after state
            $results[] = array_merge($before, [
                'data_after' => $updatedTenant->data,
                'data_type_after' => gettype($updatedTenant->data),
                'parsed_after' => json_decode($updatedTenant->data, true),
                'plan_id_after' => $updatedTenant->plan_id,
                'plan_id_changed' => $planIdChanged
            ]);
        }
        
        return response()->json([
            'message' => 'Tenant data fix attempted',
            'count' => count($results),
            'results' => $results
        ]);
    }
}

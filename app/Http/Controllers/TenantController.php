<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Notifications\TenantCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display the tenant creation form.
     */
    public function create()
    {
        return view('tenants.create');
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
            $tenant = Tenant::create(['id' => $tenantId]);
            $tenant->domains()->create(['domain' => $domain]);

            // Run migrations and create admin user
            tenancy()->initialize($tenant);
            
            // Create admin user for the tenant
            \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($password),
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
}

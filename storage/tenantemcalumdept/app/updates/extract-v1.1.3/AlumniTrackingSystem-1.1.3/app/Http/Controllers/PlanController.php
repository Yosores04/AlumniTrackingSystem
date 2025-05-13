<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index()
    {
        $plans = Plan::where('is_active', true)->get();
        return view('plans.index', compact('plans'));
    }

    /**
     * Show the plan details and subscription options.
     */
    public function show(Plan $plan)
    {
        return view('plans.show', compact('plan'));
    }

    /**
     * Subscribe to a plan.
     */
    public function subscribe(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'billing_cycle' => ['required', 'in:monthly,annual'],
        ]);

        $tenant = tenant();
        
        // Here you would integrate with a payment gateway like Stripe
        // For now, we'll just update the tenant's plan
        
        $tenant->update([
            'plan_id' => $plan->id,
            'billing_cycle' => $validated['billing_cycle'],
            'plan_expires_at' => now()->addMonths($validated['billing_cycle'] == 'annual' ? 12 : 1),
        ]);

        return redirect()->route('tenant.dashboard')
            ->with('success', "You've successfully subscribed to the {$plan->name} plan!");
    }

    /**
     * Show the current subscription status.
     */
    public function currentSubscription()
    {
        $tenant = tenant();
        $plan = $tenant->plan;
        
        if (!$plan) {
            return redirect()->route('plans.index')
                ->with('error', 'You have no active subscription. Please choose a plan.');
        }
        
        return view('plans.subscription', compact('plan', 'tenant'));
    }

    /**
     * Cancel the current subscription.
     */
    public function cancelSubscription()
    {
        $tenant = tenant();
        
        // Here you would handle cancellation with your payment gateway
        
        // For now, we'll just mark it as expiring at the end of the current period
        // but don't actually remove the plan_id yet
        
        return redirect()->route('plans.subscription')
            ->with('success', 'Your subscription has been canceled and will end on ' . 
                $tenant->plan_expires_at->format('F d, Y') . '.');
    }

    /**
     * Admin functions for managing plans
     */
    public function adminIndex()
    {
        $this->authorize('viewAny', Plan::class);
        
        $plans = Plan::all();
        return view('central.admin.plans.index', compact('plans'));
    }
    
    /**
     * Show the form for creating a new plan.
     */
    public function adminCreate()
    {
        $this->authorize('create', Plan::class);
        
        return view('central.admin.plans.create');
    }
    
    /**
     * Store a newly created plan in storage.
     */
    public function adminStore(Request $request)
    {
        $this->authorize('create', Plan::class);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:plans,slug'],
            'description' => ['nullable', 'string'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'annual_price' => ['required', 'numeric', 'min:0'],
            'max_alumni' => ['required', 'integer', 'min:0'],
            'max_instructors' => ['required', 'integer', 'min:0'],
            'has_custom_fields' => ['boolean'],
            'has_advanced_analytics' => ['boolean'],
            'has_integrations' => ['boolean'],
            'has_job_board' => ['boolean'],
            'has_custom_branding' => ['boolean'],
            'support_level' => ['required', 'in:community,email,priority'],
            'is_active' => ['boolean'],
        ]);
        
        // Handle boolean fields that might not be set in the request
        $validated['has_custom_fields'] = $request->has('has_custom_fields');
        $validated['has_advanced_analytics'] = $request->has('has_advanced_analytics');
        $validated['has_integrations'] = $request->has('has_integrations');
        $validated['has_job_board'] = $request->has('has_job_board');
        $validated['has_custom_branding'] = $request->has('has_custom_branding');
        $validated['is_active'] = $request->has('is_active');
        
        Plan::create($validated);
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully');
    }
    
    /**
     * Show the detailed view of a plan.
     */
    public function adminShow(Plan $plan)
    {
        $this->authorize('view', $plan);
        
        return view('central.admin.plans.show', compact('plan'));
    }
    
    public function adminEdit(Plan $plan)
    {
        $this->authorize('update', $plan);
        
        return view('central.admin.plans.edit', compact('plan'));
    }
    
    public function adminUpdate(Request $request, Plan $plan)
    {
        $this->authorize('update', $plan);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'annual_price' => ['required', 'numeric', 'min:0'],
            'max_alumni' => ['required', 'integer', 'min:0'],
            'max_instructors' => ['required', 'integer', 'min:0'],
            'has_custom_fields' => ['boolean'],
            'has_advanced_analytics' => ['boolean'],
            'has_integrations' => ['boolean'],
            'has_job_board' => ['boolean'],
            'has_custom_branding' => ['boolean'],
            'support_level' => ['required', 'in:community,email,priority'],
            'is_active' => ['boolean'],
        ]);
        
        // Handle boolean fields that might not be set in the request
        $validated['has_custom_fields'] = $request->has('has_custom_fields');
        $validated['has_advanced_analytics'] = $request->has('has_advanced_analytics');
        $validated['has_integrations'] = $request->has('has_integrations');
        $validated['has_job_board'] = $request->has('has_job_board');
        $validated['has_custom_branding'] = $request->has('has_custom_branding');
        $validated['is_active'] = $request->has('is_active');
        
        $plan->update($validated);
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully');
    }
} 
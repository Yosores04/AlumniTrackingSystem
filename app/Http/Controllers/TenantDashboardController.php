<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenantSettings;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TenantDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect instructors to their dashboard
        if ($user->role === User::ROLE_INSTRUCTOR) {
            return redirect()->route('instructor.dashboard');
        }
        
        // Redirect alumni to their dashboard
        if ($user->role === User::ROLE_ALUMNI) {
            // Directly accessing the alumni dashboard view without going through middleware
            $alumni = $user->alumni;
            if ($alumni) {
                return view('alumni.dashboard', compact('alumni'));
            } else {
                // Debug message if alumni relationship is missing
                Log::info('Alumni relationship is missing for user', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);
            }
        }
        
        $settings = TenantSettings::getSettings();
        
        // Get subscription plan from database
        $subscriptionPlan = $this->getSubscriptionPlan();
        
        // Make sure tenant() model is in sync with our subscription plan data
        $tenantModel = tenant();
        if ($tenantModel && isset($subscriptionPlan['plan_id'])) {
            // If we're using a tenant model that has a plan relationship, ensure it's loaded
            if (method_exists($tenantModel, 'load')) {
                $tenantModel->load('plan');
            }
            
            // Log what we're providing to the view
            Log::info('Dashboard subscription data', [
                'subscriptionPlan' => $subscriptionPlan,
                'tenant_plan' => $tenantModel->plan ?? null
            ]);
        }
        
        // Modern analytics for tenant admin dashboard
        $totalAlumni = \App\Models\Alumni::count();
        $totalInstructors = \App\Models\User::where('role', \App\Models\User::ROLE_INSTRUCTOR)->count();
        
        // Employment distribution
        $employmentStatuses = [
            'employed', 'unemployed', 'self_employed', 'student', 'other'
        ];
        $employmentDistribution = [];
        foreach ($employmentStatuses as $status) {
            $employmentDistribution[$status] = \App\Models\Alumni::where('employment_status', $status)->count();
        }
        
        // Batch year distribution (last 5 years)
        $batchYears = \App\Models\Alumni::select('batch_year')
            ->whereNotNull('batch_year')
            ->distinct()
            ->orderBy('batch_year', 'desc')
            ->take(5)
            ->pluck('batch_year');
        $batchYearDistribution = [];
        foreach ($batchYears as $year) {
            $batchYearDistribution[$year] = \App\Models\Alumni::where('batch_year', $year)->count();
        }
        
        // Initialize variables with default values to prevent undefined variable errors
        $totalJobs = 0;
        $upcomingEvents = 0;
        $totalNews = 0;
        $recentActivities = collect([]);
        $nextEvents = collect([]);
        $employedAlumni = 0;
        $furtherStudiesAlumni = 0;
        $entrepreneurAlumni = 0;
        $unemployedAlumni = 0;
        $unknownStatusAlumni = 0;
        $graduationYears = [];
        $alumniCountByYear = [];
        
        // These variables would typically be populated from database models
        // For example:
        // $totalJobs = \App\Models\Job::count();
        // $upcomingEvents = \App\Models\Event::where('start_date', '>=', now())->count();
        // $totalNews = \App\Models\News::count();
        // $recentActivities = \App\Models\Activity::latest()->take(5)->get();
        // $nextEvents = \App\Models\Event::where('start_date', '>=', now())->orderBy('start_date')->take(3)->get();
        
        return view('tenant.dashboard', [
            'settings' => $settings,
            'subscriptionPlan' => $subscriptionPlan,
            'totalAlumni' => $totalAlumni,
            'totalInstructors' => $totalInstructors,
            'employmentDistribution' => $employmentDistribution,
            'batchYearDistribution' => $batchYearDistribution,
            'totalJobs' => $totalJobs,
            'upcomingEvents' => $upcomingEvents,
            'totalNews' => $totalNews,
            'recentActivities' => $recentActivities,
            'nextEvents' => $nextEvents,
            'employedAlumni' => $employedAlumni,
            'furtherStudiesAlumni' => $furtherStudiesAlumni,
            'entrepreneurAlumni' => $entrepreneurAlumni,
            'unemployedAlumni' => $unemployedAlumni,
            'unknownStatusAlumni' => $unknownStatusAlumni,
            'graduationYears' => $graduationYears,
            'alumniCountByYear' => $alumniCountByYear
        ]);
    }
    
    /**
     * Get the current subscription plan directly from the database
     *
     * @return array
     */
    private function getSubscriptionPlan()
    {
        try {
            if (function_exists('tenant') && tenant()) {
                $tenantModel = tenant();
                // Always load the plan relationship
                if (method_exists($tenantModel, 'load')) {
                    $tenantModel->load('plan');
                }
                // Use the Eloquent relationship if available (most accurate)
                if ($tenantModel->plan) {
                    return [
                        'plan' => strtolower($tenantModel->plan->slug),
                        'plan_name' => $tenantModel->plan->name,
                        'plan_id' => $tenantModel->plan->id,
                        'billing_period_end' => $tenantModel->plan_expires_at ?? now()->addMonth()->toDateTimeString()
                    ];
                }
                // ... existing DB/manual lookup fallback ...
                $tenantId = tenant('id');
                $tenantData = DB::table('tenants')->where('id', $tenantId)->first();
                if ($tenantData && isset($tenantData->plan_id) && $tenantData->plan_id) {
                    $plan = DB::table('plans')->where('id', $tenantData->plan_id)->first();
                    if ($plan && isset($plan->slug)) {
                        $planSlug = strtolower($plan->slug);
                        $planName = $plan->name;
                        return [
                            'plan' => $planSlug,
                            'plan_name' => $planName,
                            'plan_id' => $tenantData->plan_id,
                            'billing_period_end' => $tenantData->plan_expires_at ?? now()->addMonth()->toDateTimeString()
                        ];
                    }
                }
                if ($tenantData && property_exists($tenantData, 'subscription')) {
                    $subscription = is_string($tenantData->subscription) 
                        ? json_decode($tenantData->subscription, true) 
                        : $tenantData->subscription;
                    if (is_array($subscription) && isset($subscription['plan'])) {
                        return $subscription;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving subscription plan for dashboard', [
                'error' => $e->getMessage()
            ]);
        }
        // Default to free plan if no subscription data found
        return ['plan' => 'free', 'plan_name' => 'Free Plan'];
    }
}

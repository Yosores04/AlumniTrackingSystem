<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenantSettings;

class TenantDashboardController extends Controller
{
    /**
     * Show the tenant dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get tenant settings
        $settings = TenantSettings::getSettings();
        
        // Initialize variables with default values to prevent undefined variable errors
        $totalAlumni = 0;
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
        // $totalAlumni = \App\Models\Alumni::count();
        // $totalJobs = \App\Models\Job::count();
        // $upcomingEvents = \App\Models\Event::where('start_date', '>=', now())->count();
        // $totalNews = \App\Models\News::count();
        // $recentActivities = \App\Models\Activity::latest()->take(5)->get();
        // $nextEvents = \App\Models\Event::where('start_date', '>=', now())->orderBy('start_date')->take(3)->get();
        
        return view('tenant.dashboard', compact(
            'settings', 
            'totalAlumni', 
            'totalJobs', 
            'upcomingEvents', 
            'totalNews', 
            'recentActivities', 
            'nextEvents',
            'employedAlumni',
            'furtherStudiesAlumni',
            'entrepreneurAlumni',
            'unemployedAlumni',
            'unknownStatusAlumni',
            'graduationYears',
            'alumniCountByYear'
        ));
    }
}

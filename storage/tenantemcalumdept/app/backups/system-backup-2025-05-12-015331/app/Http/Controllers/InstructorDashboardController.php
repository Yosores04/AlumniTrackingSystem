<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InstructorDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', \App\Http\Middleware\EnsureInstructor::class]);
    }

    /**
     * Show the instructor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $instructor = Auth::user();
        $settings = \App\Models\TenantSettings::getSettings();
        
        // Get alumni statistics
        $totalAlumni = \App\Models\Alumni::count();
        $newAlumni = \App\Models\Alumni::where('created_at', '>=', now()->startOfMonth())->count();
        $employedAlumni = \App\Models\Alumni::where('employment_status', 'employed')->count();
        
        // Get recently added alumni
        $recentAlumni = \App\Models\Alumni::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('tenant.instructor.dashboard', [
            'instructor' => $instructor,
            'settings' => $settings,
            'totalAlumni' => $totalAlumni,
            'newAlumni' => $newAlumni,
            'employedAlumni' => $employedAlumni,
            'recentAlumni' => $recentAlumni,
        ]);
    }
} 
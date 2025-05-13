<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alumni;

class AlumniDashboardController extends Controller
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
     * Show the alumni dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        $settings = \App\Models\TenantSettings::getSettings();
        
        return view('alumni.dashboard', compact('alumni', 'settings'));
    }
    
    /**
     * Show the alumni profile form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        $settings = \App\Models\TenantSettings::getSettings();
        
        return view('alumni.profile', compact('alumni', 'settings'));
    }
    
    /**
     * Update the alumni profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'batch_year' => 'nullable|integer|min:1950|max:' . (date('Y') + 5),
            'graduation_date' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'degree' => 'nullable|string|max:255',
            'employment_status' => 'nullable|in:employed,unemployed,self_employed,student,other',
            'current_employer' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'skills' => 'nullable|string',
            'achievements' => 'nullable|string',
            'certifications' => 'nullable|string',
        ]);
        
        // Handle profile photo
        if ($request->hasFile('profile_photo')) {
            if ($alumni->profile_photo_path) {
                \Storage::disk('public')->delete($alumni->profile_photo_path);
            }
            
            $path = $request->file('profile_photo')->store('alumni/photos', 'public');
            $validated['profile_photo_path'] = $path;
        }
        
        $alumni->update($validated);
        
        return redirect()->route('alumni.profile')->with('success', 'Profile updated successfully!');
    }
}

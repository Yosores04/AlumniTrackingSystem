<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TenantSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\AlumniPdfService;

class InstructorAlumniController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware([\App\Http\Middleware\EnsureInstructor::class]);
    }

    /**
     * Display a listing of alumni.
     */
    public function index(Request $request)
    {
        $query = Alumni::query();
        
        // Search and filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('batch_year')) {
            $query->where('batch_year', $request->input('batch_year'));
        }
        
        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->input('employment_status'));
        }
        
        // Sort
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        // Get all batch years for the filter dropdown
        $batchYears = Alumni::select('batch_year')
            ->distinct()
            ->whereNotNull('batch_year')
            ->orderBy('batch_year', 'desc')
            ->pluck('batch_year');
            
        $alumni = $query->paginate(15);
        
        $settings = TenantSettings::getSettings();
        
        return view('tenant.instructor.alumni.index', [
            'alumni' => $alumni,
            'batchYears' => $batchYears,
            'filters' => $request->only(['search', 'batch_year', 'employment_status']),
            'settings' => $settings,
        ]);
    }

    /**
     * Show the form for creating a new alumni.
     */
    public function create()
    {
        $settings = TenantSettings::getSettings();
        
        return view('tenant.instructor.alumni.create', [
            'settings' => $settings,
        ]);
    }

    /**
     * Store a newly created alumni in storage.
     */
    public function store(Request $request)
    {
        // First check if an alumni with this email already exists
        $existingAlumni = Alumni::where('email', $request->email)->first();
        if ($existingAlumni) {
            return redirect()->route('instructor.alumni.create')
                ->with('error', 'An alumni record with this email already exists.')
                ->withInput();
        }
        
        // Check if a user with this email already exists
        $existingUser = \App\Models\User::where('email', $request->email)->first();
        if ($existingUser) {
            return redirect()->route('instructor.alumni.create')
                ->with('error', 'A user account with this email already exists.')
                ->withInput();
        }
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alumni',
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
            'profile_photo_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('instructor.alumni.create')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Handle verified checkbox - convert "on" to 1 if needed
        if ($request->has('is_verified')) {
            $data['is_verified'] = ($request->is_verified === 'on') ? 1 : $request->is_verified;
        } else {
            $data['is_verified'] = 0;
        }
        
        try {
            // Create the alumni record
            $alumni = Alumni::create($data);
            
            // Generate a random password
            $password = \Illuminate\Support\Str::random(10);
            
            // Create user account
            $user = new \App\Models\User([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role' => \App\Models\User::ROLE_ALUMNI,
                'force_password_change' => true, // Flag to force password change on first login
            ]);
            $user->save();
            
            // Associate user with alumni
            $alumni->user_id = $user->id;
            $alumni->save();
            
            // Send welcome email with login credentials
            $emailData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => $password,
                'login_url' => url('/login')
            ];
            
            \Illuminate\Support\Facades\Mail::to($request->email)
                ->send(new \App\Mail\AlumniAccountCreated($emailData));
            
            return redirect()->route('instructor.alumni.index')
                ->with('success', 'Alumni record created successfully. Login credentials have been sent to ' . $request->email);
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for duplicate entry error (MySQL error code 1062)
            if ($e->errorInfo[1] == 1062) {
                return redirect()->route('instructor.alumni.create')
                    ->with('error', 'An alumni record with this email already exists.')
                    ->withInput();
            }
            
            // For other database errors, rethrow
            throw $e;
        }
    }

    /**
     * Display the specified alumni.
     */
    public function show($id)
    {
        $alumni = Alumni::findOrFail($id);
        $settings = TenantSettings::getSettings();
        
        return view('tenant.instructor.alumni.show', [
            'alumni' => $alumni,
            'settings' => $settings,
        ]);
    }

    /**
     * Show the form for editing the specified alumni.
     */
    public function edit($id)
    {
        $alumni = Alumni::findOrFail($id);
        $settings = TenantSettings::getSettings();
        
        return view('tenant.instructor.alumni.edit', [
            'alumni' => $alumni,
            'settings' => $settings,
        ]);
    }

    /**
     * Update the specified alumni in storage.
     */
    public function update(Request $request, $id)
    {
        $alumni = Alumni::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alumni,email,' . $alumni->id,
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
            'profile_photo_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('instructor.alumni.edit', $alumni->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // If there was an old file-based profile photo and we're now using a URL, clean it up
        if ($alumni->profile_photo_path && $request->filled('profile_photo_url')) {
            Storage::disk('public')->delete($alumni->profile_photo_path);
            $data['profile_photo_path'] = null;
        }
        
        // Handle verified checkbox - convert "on" to 1 if needed
        if ($request->has('is_verified')) {
            $data['is_verified'] = ($request->is_verified === 'on') ? 1 : $request->is_verified;
        } else {
            $data['is_verified'] = 0;
        }
        
        try {
            $alumni->update($data);
            return redirect()->route('instructor.alumni.show', $alumni->id)
                ->with('success', 'Alumni record updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for duplicate entry error (MySQL error code 1062)
            if ($e->errorInfo[1] == 1062) {
                return redirect()->route('instructor.alumni.edit', $alumni->id)
                    ->with('error', 'Cannot update: Another alumni record with this email already exists.')
                    ->withInput();
            }
            
            // For other database errors, rethrow
            throw $e;
        }
    }

    /**
     * Remove the specified alumni from storage.
     */
    public function destroy($id)
    {
        $alumni = Alumni::findOrFail($id);
        
        // Use a database transaction to ensure both operations succeed or fail together
        DB::beginTransaction();
        
        try {
            // Delete profile photo if it exists
            if ($alumni->profile_photo_path) {
                Storage::disk('public')->delete($alumni->profile_photo_path);
            }
            
            // Get user ID before deleting alumni
            $userId = $alumni->user_id;
            
            // Set user_id to null to avoid foreign key constraint issues
            $alumni->user_id = null;
            $alumni->save();
            
            // Delete the alumni record
            $alumni->delete();
            
            // Delete the associated user account if it exists
            if ($userId) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $user->delete();
                }
            }
            
            DB::commit();
            
            return redirect()->route('instructor.alumni.index')
                ->with('success', 'Alumni record and associated user account deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            \Illuminate\Support\Facades\Log::error('Failed to delete alumni: ' . $e->getMessage());
            
            return redirect()->route('instructor.alumni.index')
                ->with('error', 'Failed to delete alumni record. Please try again.');
        }
    }
    
    /**
     * Display alumni reports and statistics.
     */
    public function reports()
    {
        $settings = TenantSettings::getSettings();
        
        // Get statistics
        $totalAlumni = Alumni::count();
        $employedAlumni = Alumni::where('employment_status', 'employed')->count();
        $unemployedAlumni = Alumni::where('employment_status', 'unemployed')->count();
        
        // Get alumni count by batch year
        $alumniByYear = Alumni::selectRaw('batch_year, count(*) as count')
            ->whereNotNull('batch_year')
            ->groupBy('batch_year')
            ->orderBy('batch_year')
            ->get();
            
        // Get alumni count by employment status
        $alumniByStatus = Alumni::selectRaw('employment_status, count(*) as count')
            ->whereNotNull('employment_status')
            ->groupBy('employment_status')
            ->get();
        
        return view('tenant.instructor.alumni.reports', [
            'settings' => $settings,
            'totalAlumni' => $totalAlumni,
            'employedAlumni' => $employedAlumni,
            'unemployedAlumni' => $unemployedAlumni,
            'alumniByYear' => $alumniByYear,
            'alumniByStatus' => $alumniByStatus,
        ]);
    }

    /**
     * Show the form to configure a report before generation.
     */
    public function reportForm()
    {
        $settings = TenantSettings::getSettings();
        return view('tenant.instructor.alumni.report-form', [
            'settings' => $settings,
        ]);
    }

    /**
     * Generate a PDF report of alumni.
     */
    public function generateReport(Request $request)
    {
        // Get all alumni without any filtering
        $alumni = Alumni::orderBy('last_name')->get();
        
        // Setup options for PDF generation - only keep orientation
        $options = [
            'title' => 'Alumni Report',
            'orientation' => $request->input('orientation', 'L') // L for landscape, P for portrait
        ];
        
        // Generate the PDF
        $pdfService = new AlumniPdfService($options);
        $pdf = $pdfService->generateReport($alumni, $options);
        
        // Set the appropriate headers for browser display (not download)
        $filename = 'alumni_report_' . date('Y-m-d_H-i') . '.pdf';
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block'
        ];
        
        // Return the response with the PDF
        return response($pdf, 200, $headers);
    }
} 
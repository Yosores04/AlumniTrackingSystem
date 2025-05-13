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

class AlumniController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
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
        
        return view('tenant.alumni.index', [
            'alumni' => $alumni,
            'batchYears' => $batchYears,
            'filters' => $request->only(['search', 'batch_year', 'employment_status']),
            'settings' => $settings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $settings = TenantSettings::getSettings();
        
        return view('tenant.alumni.create', [
            'settings' => $settings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add debugging
        \Illuminate\Support\Facades\Log::info('Alumni store method called', [
            'request_data' => $request->all(),
        ]);
        
        // First check if an alumni with this email already exists
        $existingAlumni = Alumni::where('email', $request->email)->first();
        if ($existingAlumni) {
            \Illuminate\Support\Facades\Log::info('Existing alumni found with this email');
            return redirect()->route('alumni.create')
                ->with('error', 'An alumni record with this email already exists.')
                ->withInput();
        }
        
        // Check if a user with this email already exists
        $existingUser = \App\Models\User::where('email', $request->email)->first();
        if ($existingUser) {
            \Illuminate\Support\Facades\Log::info('Existing user found with this email');
            return redirect()->route('alumni.create')
                ->with('error', 'A user account with this email already exists.')
                ->withInput();
        }
        
        \Illuminate\Support\Facades\Log::info('Validating alumni data');
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
            'linkedin_url' => 'nullable|url',
            'profile_photo_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::info('Validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->route('alumni.create')
                ->withErrors($validator)
                ->withInput();
        }

        \Illuminate\Support\Facades\Log::info('Validation passed, preparing data');
        $data = $request->all();
        
        // Handle verified checkbox - convert "on" to 1 if needed
        if ($request->has('is_verified')) {
            $data['is_verified'] = ($request->is_verified === 'on') ? 1 : $request->is_verified;
        } else {
            $data['is_verified'] = 0;
        }
        
        // Use a DB transaction to ensure we can roll back if anything fails
        DB::beginTransaction();
        
        try {
            \Illuminate\Support\Facades\Log::info('Creating alumni record');
            // Create the alumni record
            $alumni = Alumni::create($data);

            \Illuminate\Support\Facades\Log::info('Alumni record created', ['alumni_id' => $alumni->id]);

            // Generate a random password
            $password = \Illuminate\Support\Str::random(10);
            
            \Illuminate\Support\Facades\Log::info('Creating user account');
            // Create user account
            $user = new \App\Models\User([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role' => \App\Models\User::ROLE_ALUMNI,
                'force_password_change' => true, // Flag to force password change on first login
            ]);
            $user->save();
            
            \Illuminate\Support\Facades\Log::info('User account created', ['user_id' => $user->id]);
            
            // Associate user with alumni
            $alumni->user_id = $user->id;
            $alumni->save();
            
            // Commit transaction - all database operations are now persisted
            DB::commit();
            
            try {
                \Illuminate\Support\Facades\Log::info('Preparing to send welcome email');
                // Send welcome email with login credentials
                $emailData = [
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'password' => $password,
                    'login_url' => url('/login')
                ];
                
                // Temporarily disable email sending for debugging
                // \Illuminate\Support\Facades\Mail::to($request->email)
                //    ->send(new \App\Mail\AlumniAccountCreated($emailData));
                
                \Illuminate\Support\Facades\Log::info('Welcome email would be sent (currently disabled for debugging)');

                // Email sending re-enabled
                \Illuminate\Support\Facades\Mail::to($request->email)
                   ->send(new \App\Mail\AlumniAccountCreated($emailData));
                
                \Illuminate\Support\Facades\Log::info('Welcome email sent successfully');
            } catch (\Exception $emailError) {
                // Log the email error but don't fail the whole operation
                \Illuminate\Support\Facades\Log::error('Failed to send welcome email', [
                    'error' => $emailError->getMessage(),
                    'trace' => $emailError->getTraceAsString()
                ]);
            }

            \Illuminate\Support\Facades\Log::info('Process completed successfully');
            return redirect()->route('alumni.index')
                ->with('success', 'Alumni record created successfully. The account was created with email: ' . $request->email . ' and password: ' . $password);
        } catch (\Exception $e) {
            // Roll back transaction if any step fails
            DB::rollBack();
            
            \Illuminate\Support\Facades\Log::error('Error creating alumni record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check for duplicate entry error (MySQL error code 1062)
            if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                return redirect()->route('alumni.create')
                    ->with('error', 'An alumni record with this email already exists.')
                    ->withInput();
            }
            
            // Show a generic error message for other exceptions
            return redirect()->route('alumni.create')
                ->with('error', 'An error occurred while creating the alumni record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alumni = Alumni::findOrFail($id);
        $settings = TenantSettings::getSettings();
        
        return view('tenant.alumni.show', [
            'alumni' => $alumni,
            'settings' => $settings,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $alumni = Alumni::findOrFail($id);
        $settings = TenantSettings::getSettings();
        
        return view('tenant.alumni.edit', [
            'alumni' => $alumni,
            'settings' => $settings,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
            return redirect()->route('alumni.edit', $alumni->id)
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
            // If there was an old file-based profile photo and we're now using a URL, clean it up
            if ($alumni->profile_photo_path && $request->filled('profile_photo_url')) {
                Storage::disk('public')->delete($alumni->profile_photo_path);
                $alumni->profile_photo_path = null;
            }
            
            $alumni->update($data);
            return redirect()->route('alumni.show', $alumni->id)
                ->with('success', 'Alumni record updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for duplicate entry error (MySQL error code 1062)
            if ($e->errorInfo[1] == 1062) {
                return redirect()->route('alumni.edit', $alumni->id)
                    ->with('error', 'Cannot update: Another alumni record with this email already exists.')
                    ->withInput();
            }
            
            // For other database errors, rethrow
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alumni = Alumni::findOrFail($id);
        
        // Use a database transaction to ensure both operations succeed or fail together
        DB::beginTransaction();
        
        try {
            // Delete profile photo if it exists (legacy files that were uploaded previously)
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
            
            return redirect()->route('alumni.index')
                ->with('success', 'Alumni record and associated user account deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            \Illuminate\Support\Facades\Log::error('Failed to delete alumni: ' . $e->getMessage());
            
            return redirect()->route('alumni.index')
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
        
        return view('tenant.alumni.reports', [
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
        return view('tenant.alumni.report-form', [
            'settings' => $settings,
        ]);
    }

    /**
     * Generate a PDF report of alumni.
     */
    public function generateReport(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('PDF generation error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->view('errors.500', [
                'message' => 'Failed to generate PDF report. Error: ' . $e->getMessage(),
                'settings' => TenantSettings::getSettings()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TenantSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('alumni.create')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('profile_photo');
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('alumni/photos', 'public');
            $data['profile_photo_path'] = $path;
        }
        
        $alumni = Alumni::create($data);

        return redirect()->route('alumni.index')
            ->with('success', 'Alumni record created successfully.');
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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('alumni.edit', $alumni->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['profile_photo', '_token', '_method']);
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($alumni->profile_photo_path) {
                Storage::disk('public')->delete($alumni->profile_photo_path);
            }
            
            $path = $request->file('profile_photo')->store('alumni/photos', 'public');
            $data['profile_photo_path'] = $path;
        }
        
        // Handle verified checkbox
        $data['is_verified'] = $request->has('is_verified');
        
        $alumni->update($data);

        return redirect()->route('alumni.show', $alumni->id)
            ->with('success', 'Alumni record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alumni = Alumni::findOrFail($id);
        
        // Delete profile photo if it exists
        if ($alumni->profile_photo_path) {
            Storage::disk('public')->delete($alumni->profile_photo_path);
        }
        
        $alumni->delete();

        return redirect()->route('alumni.index')
            ->with('success', 'Alumni record deleted successfully.');
    }
    
    /**
     * Display the import alumni form.
     */
    public function importForm()
    {
        $settings = TenantSettings::getSettings();
        
        return view('tenant.alumni.import', [
            'settings' => $settings,
        ]);
    }
    
    /**
     * Process the import of alumni data.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('alumni.import')
                ->withErrors($validator);
        }

        // Process the CSV file
        // Implementation would handle file parsing and database insertion
        
        return redirect()->route('alumni.index')
            ->with('success', 'Alumni data imported successfully.');
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
}

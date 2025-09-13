<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\EmploymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $alumni)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $employmentHistories = $alumni->employmentHistories()->paginate(10);
        
        return view('employment-history.index', compact('alumni', 'employmentHistories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $alumni)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        return view('employment-history.create', compact('alumni'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $alumni)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'employment_type' => 'required|in:full_time,part_time,contract,internship,freelance',
            'salary' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'achievements' => 'nullable|string',
            'skills' => 'nullable|array',
            'is_current' => 'boolean',
        ]);

        // If this is marked as current, unmark any other current positions
        if ($validated['is_current'] ?? false) {
            $alumni->employmentHistories()->where('is_current', true)->update(['is_current' => false]);
        }

        $alumni->employmentHistories()->create($validated);

        return redirect()->route('alumni.employment-history.index', $alumni)
                        ->with('success', 'Employment history added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $alumni, EmploymentHistory $employmentHistory)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        return view('employment-history.show', compact('alumni', 'employmentHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $alumni, EmploymentHistory $employmentHistory)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        return view('employment-history.edit', compact('alumni', 'employmentHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $alumni, EmploymentHistory $employmentHistory)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'employment_type' => 'required|in:full_time,part_time,contract,internship,freelance',
            'salary' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'achievements' => 'nullable|string',
            'skills' => 'nullable|array',
            'is_current' => 'boolean',
        ]);

        // If this is marked as current, unmark any other current positions
        if ($validated['is_current'] ?? false) {
            $alumni->employmentHistories()
                   ->where('id', '!=', $employmentHistory->id)
                   ->where('is_current', true)
                   ->update(['is_current' => false]);
        }

        $employmentHistory->update($validated);

        return redirect()->route('alumni.employment-history.index', $alumni)
                        ->with('success', 'Employment history updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $alumni, EmploymentHistory $employmentHistory)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $employmentHistory->delete();

        return redirect()->route('alumni.employment-history.index', $alumni)
                        ->with('success', 'Employment history deleted successfully.');
    }
}

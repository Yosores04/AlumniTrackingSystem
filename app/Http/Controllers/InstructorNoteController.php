<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\InstructorNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $alumni)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $user = Auth::user();
        $query = $alumni->instructorNotes();
        
        // If not an instructor/admin, only show public notes
        if (!in_array($user->role, ['instructor', 'tenant_admin', 'central_admin'])) {
            $query->public();
        }
        
        $instructorNotes = $query->with('instructor')->paginate(10);
        
        return view('instructor-notes.index', compact('alumni', 'instructorNotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $alumni)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        return view('instructor-notes.create', compact('alumni'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $alumni)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $validated = $request->validate([
            'note' => 'required|string',
            'note_type' => 'required|in:academic,behavioral,performance,general',
            'priority' => 'required|in:low,medium,high',
            'is_private' => 'boolean',
            'note_date' => 'nullable|date',
        ]);

        $validated['instructor_id'] = Auth::id();
        $validated['note_date'] = $validated['note_date'] ?? now();

        $alumni->instructorNotes()->create($validated);

        return redirect()->route('alumni.instructor-notes.index', $alumni)
                        ->with('success', 'Instructor note added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $alumni, InstructorNote $instructorNote)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $user = Auth::user();
        
        // Check if user can view this specific note
        if ($instructorNote->is_private && 
            !in_array($user->role, ['instructor', 'tenant_admin', 'central_admin']) &&
            $instructorNote->instructor_id !== $user->id) {
            abort(403, 'This note is private.');
        }
        
        return view('instructor-notes.show', compact('alumni', 'instructorNote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $alumni, InstructorNote $instructorNote)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        return view('instructor-notes.edit', compact('alumni', 'instructorNote'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $alumni, InstructorNote $instructorNote)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $validated = $request->validate([
            'note' => 'required|string',
            'note_type' => 'required|in:academic,behavioral,performance,general',
            'priority' => 'required|in:low,medium,high',
            'is_private' => 'boolean',
            'note_date' => 'nullable|date',
        ]);

        $instructorNote->update($validated);

        return redirect()->route('alumni.instructor-notes.index', $alumni)
                        ->with('success', 'Instructor note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $alumni, InstructorNote $instructorNote)
    {
        $alumni = Alumni::findOrFail($alumni);
        
        $instructorNote->delete();

        return redirect()->route('alumni.instructor-notes.index', $alumni)
                        ->with('success', 'Instructor note deleted successfully.');
    }
}

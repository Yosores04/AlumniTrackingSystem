<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware([\App\Http\Middleware\EnsureTenantAdmin::class]);
    }

    /**
     * Display a listing of instructors.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $instructors = User::where('role', User::ROLE_INSTRUCTOR);
        
        if ($search) {
            $instructors->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $instructors = $instructors->orderBy('name')->paginate(10);
        
        return view('tenant.instructors.index', compact('instructors', 'search'));
    }

    /**
     * Show the form for creating a new instructor.
     */
    public function create()
    {
        return view('tenant.instructors.create');
    }

    /**
     * Store a newly created instructor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_INSTRUCTOR,
        ]);

        return redirect()->route('tenant.instructors.index')
            ->with('success', 'Instructor created successfully');
    }

    /**
     * Display the specified instructor.
     */
    public function show($id)
    {
        $instructor = User::where('id', $id)
                        ->where('role', User::ROLE_INSTRUCTOR)
                        ->firstOrFail();
                
        return view('tenant.instructors.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified instructor.
     */
    public function edit($id)
    {
        $instructor = User::where('id', $id)
                        ->where('role', User::ROLE_INSTRUCTOR)
                        ->firstOrFail();
                
        return view('tenant.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified instructor in storage.
     */
    public function update(Request $request, $id)
    {
        $instructor = User::where('id', $id)
                        ->where('role', User::ROLE_INSTRUCTOR)
                        ->firstOrFail();
                
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($instructor->id)],
        ]);

        $instructor->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            
            $instructor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('tenant.instructors.index')
            ->with('success', 'Instructor updated successfully');
    }

    /**
     * Remove the specified instructor from storage.
     */
    public function destroy($id)
    {
        $instructor = User::where('id', $id)
                        ->where('role', User::ROLE_INSTRUCTOR)
                        ->firstOrFail();
                        
        $instructor->delete();

        return redirect()->route('tenant.instructors.index')
            ->with('success', 'Instructor deleted successfully');
    }
} 
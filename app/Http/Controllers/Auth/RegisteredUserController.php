<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the tenant registration view (for alumni).
     */
    public function tenantCreate(): View
    {
        return view('tenant.auth.register');
    }

    /**
     * Handle tenant registration request.
     */
    public function tenantStore(Request $request): RedirectResponse
    {
        return $this->store($request);
    }

    /**
     * Display the registration view (legacy method).
     */
    public function create(): View
    {
        // Determine if we're on tenant domain
        $isCentral = in_array(request()->getHost(), config('tenancy.central_domains'));
        
        if ($isCentral) {
            // Should not reach here as central registration is disabled
            abort(404);
        }
        
        return $this->tenantCreate();
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Extract first and last name from full name
        $nameParts = explode(' ', $request->name, 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_ALUMNI, // Set role to alumni
        ]);

        // Check if an alumni with this email already exists
        $existingAlumni = \App\Models\Alumni::where('email', $request->email)->first();
        
        if ($existingAlumni) {
            // Associate existing alumni with the new user
            $existingAlumni->user_id = $user->id;
            $existingAlumni->save();
            
            // Flash a notification message
            session()->flash('info', 'An alumni record with this email already exists. It has been connected to your new account.');
        } else {
            // Create corresponding alumni record
            $alumni = new \App\Models\Alumni([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
                'is_verified' => false, // Alumni accounts need to be verified by admin
            ]);
            
            try {
                $user->alumni()->save($alumni);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error code
                    // Find the existing alumni record and associate it with this user
                    $duplicateAlumni = \App\Models\Alumni::where('email', $request->email)->first();
                    if ($duplicateAlumni) {
                        $duplicateAlumni->user_id = $user->id;
                        $duplicateAlumni->save();
                        
                        session()->flash('info', 'An alumni record with this email already exists. It has been connected to your new account.');
                    }
                } else {
                    // For other database errors, rethrow
                    throw $e;
                }
            }
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect to the alumni dashboard route now that the middleware is not required
        return redirect(route('alumni.dashboard'))
            ->with('success', 'Welcome to BukSU AlumniConnect, ' . $user->name . '! Your account has been created successfully.');
    }
}

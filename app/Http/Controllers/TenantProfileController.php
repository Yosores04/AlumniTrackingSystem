<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;

class TenantProfileController extends Controller
{
    /**
     * Display the tenant admin's profile form.
     */
    public function edit(Request $request)
    {
        return view('tenant.profile.edit', [
            'user' => $request->user(),
            'settings' => \App\Models\TenantSettings::getSettings(),
        ]);
    }

    /**
     * Update the tenant admin's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('tenant.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the tenant admin's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Rules\Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('tenant.profile.edit')->with('status', 'password-updated');
    }
} 
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function show()
    {
        return view('auth.change-password');
    }

    /**
     * Handle password change request.
     */
    public function update(ChangePasswordRequest $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            // Log the password change
            logger('Password changed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'timestamp' => now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->with('success', 'Password changed successfully! Please use your new password for future logins.');
            
        } catch (\Exception $e) {
            logger('Password change failed', [
                'user_id' => optional(Auth::user())->id,
                'error' => $e->getMessage(),
                'timestamp' => now(),
            ]);
            
            return back()->withErrors(['error' => 'An error occurred while changing your password. Please try again.']); 
        }
    }
}

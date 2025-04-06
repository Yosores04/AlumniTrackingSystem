<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Notifications\LoginNotification;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Authentication failed.');
        }

        $email = $googleUser->getEmail();
        $role = 'user';
        
        if (str_ends_with($email, '@gmail.com')) {
            $role = 'instructor';
        } elseif (str_ends_with($email, '@student.buksu.edu.ph')) {
            $role = 'student';
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(24)),
                'role' => $role,
            ]
        );
        
        Auth::login($user, true);
        
        // Directly send the notification instead of using the event system
        // This avoids the serialization issue with PDO
        $user->notify(new LoginNotification(request()->ip(), request()->userAgent()));
        
        return redirect('/dashboard');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Removed Laravel\Sanctum\HasApiTokens until it's installed

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Removed HasApiTokens

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if the user's password has expired
     */
    public function hasExpiredPassword()
    {
        return $this->password_expires_at && $this->password_expires_at->isPast();
    }
}

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
     * Role constants
     */
    const ROLE_CENTRAL_ADMIN = 'central_admin';
    const ROLE_TENANT_ADMIN = 'tenant_admin';
    const ROLE_INSTRUCTOR = 'instructor';
    const ROLE_ALUMNI = 'alumni';
    const ROLE_USER = 'user';

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
        'role',
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
    
    /**
     * Check if the user is a central admin
     */
    public function isCentralAdmin()
    {
        return $this->role === self::ROLE_CENTRAL_ADMIN;
    }
    
    /**
     * Check if the user is a tenant admin
     */
    public function isTenantAdmin()
    {
        return $this->role === self::ROLE_TENANT_ADMIN;
    }
    
    /**
     * Check if the user is any type of admin (central or tenant)
     */
    public function isAdmin()
    {
        return $this->isCentralAdmin() || $this->isTenantAdmin();
    }
    
    /**
     * Check if the user is an instructor
     */
    public function isInstructor()
    {
        return $this->role === self::ROLE_INSTRUCTOR;
    }
    
    /**
     * Get the alumni profile associated with the user.
     */
    public function alumni()
    {
        return $this->hasOne(Alumni::class);
    }
}

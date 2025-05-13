<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Support\Facades\Log;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'subscription' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 
        'data', 
        'status',
        'subscription',
        'plan_id',
        'billing_cycle',
        'plan_expires_at'
    ];

    /**
     * Get the data attribute with proper array handling
     *
     * @param  mixed  $value
     * @return array
     */
    public function getDataAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        
        return $value ?: [];
    }

    /**
     * Set the data attribute with proper JSON encoding
     *
     * @param  mixed  $value
     * @return void
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get the suspended status.
     *
     * @return bool
     */
    public function getIsSuspendedAttribute()
    {
        return $this->status === 'suspended';
    }

    /**
     * Get the inactive status.
     *
     * @return bool
     */
    public function getIsInactiveAttribute()
    {
        return $this->status === 'inactive';
    }

    /**
     * Get the active status.
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    /**
     * Get the plan associated with this tenant.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if the tenant has reached alumni limit.
     */
    public function hasReachedAlumniLimit()
    {
        if (!$this->plan) {
            return true; // No plan means no access
        }

        if ($this->plan->hasUnlimitedAlumni()) {
            return false; // Unlimited
        }

        // Count alumni in the tenant database
        // This is a placeholder - implement the actual count method based on your application
        $alumniCount = 0; // Replace with actual count
        
        return $alumniCount >= $this->plan->max_alumni;
    }

    /**
     * Check if the tenant has reached instructor limit.
     */
    public function hasReachedInstructorLimit()
    {
        if (!$this->plan) {
            return true; // No plan means no access
        }

        if ($this->plan->hasUnlimitedInstructors()) {
            return false; // Unlimited
        }

        // Count instructors in the tenant database
        $instructorCount = User::where('role', User::ROLE_INSTRUCTOR)->count();
        
        return $instructorCount >= $this->plan->max_instructors;
    }

    /**
     * Check if subscription is active.
     */
    public function hasActiveSubscription()
    {
        if (!$this->plan_id) {
            return false;
        }

        if ($this->plan->slug === 'free') {
            return true; // Free plan is always active
        }

        return !$this->plan_expires_at || $this->plan_expires_at > now();
    }
}
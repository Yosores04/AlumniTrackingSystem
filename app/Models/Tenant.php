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
        'subscription'
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
}
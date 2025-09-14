<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'status_type',
        'status',
        'notes',
        'metadata',
        'effective_date',
        'expiry_date',
        'is_current',
        'updated_by',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'is_current' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the alumni that owns this tracking status
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Get the user who updated this status
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who verified this status
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope for current statuses
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope for verified statuses
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope for filtering by status type
     */
    public function scopeType($query, $type)
    {
        return $query->where('status_type', $type);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for active statuses (current and not expired)
     */
    public function scopeActive($query)
    {
        return $query->where('is_current', true)
                    ->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>=', now());
                    });
    }

    /**
     * Get the status badge color based on status type and value
     */
    public function getStatusBadgeColorAttribute(): string
    {
        $colors = [
            'contact' => [
                'active' => 'bg-green-100 text-green-800',
                'inactive' => 'bg-red-100 text-red-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
            ],
            'employment' => [
                'employed' => 'bg-blue-100 text-blue-800',
                'unemployed' => 'bg-gray-100 text-gray-800',
                'self_employed' => 'bg-purple-100 text-purple-800',
                'student' => 'bg-indigo-100 text-indigo-800',
            ],
            'engagement' => [
                'high' => 'bg-green-100 text-green-800',
                'medium' => 'bg-yellow-100 text-yellow-800',
                'low' => 'bg-red-100 text-red-800',
            ],
        ];

        return $colors[$this->status_type][$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayNameAttribute(): string
    {
        $names = [
            'contact' => [
                'active' => 'Contact Active',
                'inactive' => 'Contact Inactive',
                'pending' => 'Pending Contact',
            ],
            'employment' => [
                'employed' => 'Employed',
                'unemployed' => 'Unemployed',
                'self_employed' => 'Self-Employed',
                'student' => 'Student',
            ],
            'engagement' => [
                'high' => 'High Engagement',
                'medium' => 'Medium Engagement',
                'low' => 'Low Engagement',
            ],
        ];

        return $names[$this->status_type][$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Check if the status is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date < now();
    }
}

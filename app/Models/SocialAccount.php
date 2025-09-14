<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'platform',
        'username',
        'profile_url',
        'is_verified',
        'is_public',
        'metadata',
        'last_updated',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_public' => 'boolean',
        'metadata' => 'array',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the alumni that owns this social account
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Scope for getting verified accounts
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for getting public accounts
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for filtering by platform
     */
    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Get the platform display name
     */
    public function getPlatformDisplayNameAttribute(): string
    {
        $platforms = [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter/X',
            'instagram' => 'Instagram',
            'github' => 'GitHub',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'website' => 'Personal Website',
        ];

        return $platforms[$this->platform] ?? ucfirst($this->platform);
    }

    /**
     * Get the platform icon class
     */
    public function getPlatformIconAttribute(): string
    {
        $icons = [
            'facebook' => 'fab fa-facebook',
            'linkedin' => 'fab fa-linkedin',
            'twitter' => 'fab fa-twitter',
            'instagram' => 'fab fa-instagram',
            'github' => 'fab fa-github',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'website' => 'fas fa-globe',
        ];

        return $icons[$this->platform] ?? 'fas fa-link';
    }
}

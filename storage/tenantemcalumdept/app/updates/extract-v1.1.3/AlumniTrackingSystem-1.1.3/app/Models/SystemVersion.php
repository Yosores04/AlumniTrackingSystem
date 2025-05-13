<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemVersion extends Model
{
    protected $fillable = [
        'version',
        'release_tag',
        'github_url',
        'description',
        'changelog',
        'backup_path',
        'is_active',
        'is_current',
        'installed_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_current' => 'boolean',
        'installed_at' => 'datetime',
    ];

    /**
     * Get the current active version of the system
     *
     * @return SystemVersion|null
     */
    public static function getCurrentVersion()
    {
        return self::where('is_current', true)->first();
    }

    /**
     * Get all available versions that can be rolled back to
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAvailableVersions()
    {
        return self::where('is_active', true)
            ->where('is_current', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mark this version as the current version
     *
     * @return bool
     */
    public function markAsCurrent()
    {
        // First, unmark all current versions
        self::where('is_current', true)->update(['is_current' => false]);
        
        // Mark this one as current
        $this->is_current = true;
        $this->is_active = true;
        $this->installed_at = now();
        
        return $this->save();
    }

    /**
     * Get the semantic version (without 'v' prefix)
     *
     * @return string
     */
    public function getSemanticVersionAttribute()
    {
        return ltrim($this->version, 'v');
    }

    /**
     * Format the installed date
     *
     * @return string
     */
    public function getFormattedInstalledDateAttribute()
    {
        return $this->installed_at ? $this->installed_at->format('M d, Y h:i A') : 'Not installed';
    }
}

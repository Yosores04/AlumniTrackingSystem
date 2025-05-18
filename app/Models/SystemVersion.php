<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * Check GitHub API connectivity and rate limits
     *
     * @return array Information about GitHub API status
     */
    public static function checkGitHubStatus()
    {
        $result = [
            'success' => false,
            'has_token' => false,
            'rate_limit' => 0,
            'rate_limit_remaining' => 0,
            'rate_limit_reset' => null,
            'reset_time_formatted' => null,
            'message' => '',
        ];
        
        try {
            // Setup GitHub API headers
            $headers = ['User-Agent' => 'Alumni-Tracking-System-Updater'];
            
            // Add token if available
            $token = config('services.github.token');
            if ($token) {
                // GitHub API now requires 'Bearer' prefix for token authentication
                // Support both formats (with or without 'Bearer') to ensure compatibility
                if (stripos($token, 'Bearer') === 0) {
                    $headers['Authorization'] = $token;
                } else if (stripos($token, 'token') === 0) {
                    $headers['Authorization'] = $token;
                } else {
                    $headers['Authorization'] = 'Bearer ' . $token;
                }
                $result['has_token'] = true;
            }
            
            // Call GitHub API rate limit endpoint
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get('https://api.github.com/rate_limit');
            
            // Log the response for debugging
            \Log::debug('GitHub Rate Limit Response: ' . $response->status() . ' - ' . substr($response->body(), 0, 500));
            
            if ($response->successful()) {
                $rateData = $response->json();
                $coreLimit = $rateData['resources']['core'] ?? [];
                
                $result['success'] = true;
                $result['rate_limit'] = $coreLimit['limit'] ?? 0;
                $result['rate_limit_remaining'] = $coreLimit['remaining'] ?? 0;
                $result['rate_limit_reset'] = $coreLimit['reset'] ?? null;
                
                if ($result['rate_limit_reset']) {
                    $result['reset_time_formatted'] = date('Y-m-d H:i:s', $result['rate_limit_reset']);
                }
                
                $result['message'] = "GitHub API is accessible. Rate limit: {$result['rate_limit_remaining']}/{$result['rate_limit']} remaining.";
                
                // Log the status
                \Log::info('GitHub API status check successful', $result);
            } else {
                $result['message'] = "Failed to access GitHub API. Status code: {$response->status()}";
                \Log::warning('GitHub API status check failed', [
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                ]);
                
                // Check for specific error codes
                if ($response->status() == 401) {
                    $result['message'] = "GitHub API authentication failed. Please check your token.";
                } else if ($response->status() == 403) {
                    $result['message'] = "GitHub API access forbidden. This might be due to rate limiting or permissions issues.";
                }
            }
        } catch (\Exception $e) {
            $result['message'] = "Error checking GitHub API status: {$e->getMessage()}";
            \Log::error('Error checking GitHub API status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
        
        return $result;
    }

    /**
     * Compare semantic versions to determine if this version is newer than another
     *
     * @param string $version Version to compare with (e.g. v1.0.0)
     * @return bool True if this version is newer
     */
    public function isNewerThan($version)
    {
        $v1 = ltrim($this->version, 'v');
        $v2 = ltrim($version, 'v');
        
        return version_compare($v1, $v2, '>');
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

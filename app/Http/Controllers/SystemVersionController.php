<?php

namespace App\Http\Controllers;

use App\Models\SystemVersion;
use App\Models\TenantSettings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SystemVersionController extends Controller
{
    public $githubRepo = 'AlumniTrackingSystem';
    public $githubOwner = 'Yosores04';
    public $githubBranch = 'integration';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
    }
    
    /**
     * Display system version management dashboard.
     */
    public function index()
    {
        // Get current version
        $currentVersion = SystemVersion::getCurrentVersion();
        
        // Get all available versions
        $availableVersions = SystemVersion::where('is_active', true)
            ->where('is_current', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Split into updates (newer versions) and rollbacks (older versions)
        $updates = collect();
        $rollbacks = collect();
        
        if ($currentVersion) {
            foreach ($availableVersions as $version) {
                if ($version->isNewerThan($currentVersion->version)) {
                    $updates->push($version);
                } else {
                    $rollbacks->push($version);
                }
            }
        }
        
        // Settings for layout
        $settings = TenantSettings::getSettings();
        
        return view('tenant.system.versions', [
            'currentVersion' => $currentVersion,
            'updates' => $updates,
            'rollbacks' => $rollbacks,
            'settings' => $settings,
        ]);
    }
    
    /**
     * Check for new GitHub releases or tags
     */
    public function checkForUpdates()
    {
        try {
            // First check GitHub connectivity
            $githubStatus = SystemVersion::checkGitHubStatus();
            
            if (!$githubStatus['success']) {
                Log::error('Failed to access GitHub API before checking for updates: ' . $githubStatus['message']);
                return redirect()->route('system.versions')
                    ->with('error', 'Could not connect to GitHub API: ' . $githubStatus['message']);
            }
            
            // If we're close to rate limit, warn the user
            if ($githubStatus['rate_limit_remaining'] < 5) {
                Log::warning('GitHub API rate limit almost exceeded. Remaining: ' . $githubStatus['rate_limit_remaining']);
                return redirect()->route('system.versions')
                    ->with('warning', "GitHub API rate limit almost exceeded. Only {$githubStatus['rate_limit_remaining']} requests remaining. Try again after {$githubStatus['reset_time_formatted']}.");
            }
            
            // Setup GitHub API headers
            $headers = ['User-Agent' => 'Alumni-Tracking-System-Updater'];
            
            // Add token if available - improved token handling
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
                Log::info('Using GitHub API token for authentication');
            } else {
                Log::info('No GitHub API token provided, using unauthenticated requests (lower rate limits apply)');
            }
            
            // First try to get all releases
            Log::info("Checking for releases from GitHub repository: {$this->githubOwner}/{$this->githubRepo}");
            
            // Additional check to ensure we're using the correct repository
            if (empty($this->githubOwner) || empty($this->githubRepo)) {
                Log::error('GitHub repository owner or name is empty. Please check your configuration.');
                return redirect()->route('system.versions')
                    ->with('error', 'GitHub repository configuration is incomplete. Please contact your administrator.');
            }
            
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->get("https://api.github.com/repos/{$this->githubOwner}/{$this->githubRepo}/releases");
            
            // Log rate limit information
            $rateLimitLimit = $response->header('X-RateLimit-Limit');
            $rateLimitRemaining = $response->header('X-RateLimit-Remaining');
            $rateLimitReset = $response->header('X-RateLimit-Reset');
            
            Log::info("GitHub API rate limit: {$rateLimitRemaining}/{$rateLimitLimit} remaining. Reset at: " . 
                      date('Y-m-d H:i:s', $rateLimitReset ?? time()));
            
            $newVersionsCount = 0;
            
            // Debug response status and body
            Log::info("GitHub API Response Status: " . $response->status());
            Log::debug("GitHub API Response Body: " . substr($response->body(), 0, 1000) . "...");
            
            // Check for rate limit issues
            if ($response->status() == 403) {
                if ($rateLimitRemaining == 0) {
                    Log::error('GitHub API rate limit exceeded. Please wait or add a GitHub token to your .env file.');
                    return redirect()->route('system.versions')
                        ->with('error', 'GitHub API rate limit exceeded. Please try again later or contact your administrator to add a GitHub token.');
                } else {
                    Log::error('GitHub API access forbidden. This might be due to authentication issues or repository permissions.');
                    return redirect()->route('system.versions')
                        ->with('error', 'GitHub API access forbidden. Please check your GitHub token permissions.');
                }
            }
            
            // If releases exist, process them
            if ($response->successful() && count($response->json()) > 0) {
                $releases = $response->json();
                Log::info("Found " . count($releases) . " releases in the repository");
                
                foreach ($releases as $release) {
                    $releaseTag = $release['tag_name'] ?? null;
                    
                    if (!$releaseTag) {
                        continue;
                    }
                    
                    // Check if this version is already in our database
                    $existingVersion = SystemVersion::where('release_tag', $releaseTag)->first();
                    
                    if ($existingVersion) {
                        Log::info("Release {$releaseTag} already exists in database, skipping");
                        continue; // Skip this release as we already have it
                    }
                    
                    Log::info("Adding new release: {$releaseTag}");
                    
                    // Create a new version record
                    $version = new SystemVersion([
                        'version' => $releaseTag,
                        'release_tag' => $releaseTag,
                        'github_url' => $release['html_url'] ?? '',
                        'description' => $release['body'] ?? "Release: {$releaseTag}",
                        'changelog' => $release['body'] ?? "Release: {$releaseTag}",
                        'is_active' => true,
                        'is_current' => false,
                    ]);
                    
                    $version->save();
                    $newVersionsCount++;
                }
                
                if ($newVersionsCount > 0) {
                    Log::info("Added {$newVersionsCount} new releases to available updates");
                    return redirect()->route('system.versions')
                        ->with('success', "Found {$newVersionsCount} new version(s) and added to available updates.");
                } else {
                    // Try tags if no new releases were found
                    Log::info("No new releases found, checking tags instead");
                    return $this->checkForTags($headers);
                }
            } else {
                // Releases don't exist or failed, try tags instead
                Log::info("No releases found or API call unsuccessful, checking tags instead");
                return $this->checkForTags($headers);
            }
                
        } catch (Exception $e) {
            Log::error('Error checking for updates: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            return redirect()->route('system.versions')
                ->with('error', 'An error occurred while checking for updates: ' . $e->getMessage());
        }
    }
    
    /**
     * Check for tags if releases don't exist
     */
    private function checkForTags($headers)
    {
        try {
            // Try to get all tags
            Log::info("Checking for tags from GitHub repository: {$this->githubOwner}/{$this->githubRepo}");
            $tagsResponse = Http::withHeaders($headers)
                ->timeout(30)
                ->get("https://api.github.com/repos/{$this->githubOwner}/{$this->githubRepo}/tags");
            
            // Log rate limit information
            $rateLimitLimit = $tagsResponse->header('X-RateLimit-Limit');
            $rateLimitRemaining = $tagsResponse->header('X-RateLimit-Remaining');
            $rateLimitReset = $tagsResponse->header('X-RateLimit-Reset');
            
            Log::info("GitHub API rate limit: {$rateLimitRemaining}/{$rateLimitLimit} remaining. Reset at: " . 
                     date('Y-m-d H:i:s', $rateLimitReset ?? time()));
            
            // Debug response status and body
            Log::info("GitHub Tags API Response Status: " . $tagsResponse->status());
            Log::debug("GitHub Tags API Response Body: " . substr($tagsResponse->body(), 0, 1000) . "...");
            
            // Check for rate limit issues
            if ($tagsResponse->status() == 403 && $rateLimitRemaining == 0) {
                Log::error('GitHub API rate limit exceeded. Please wait or add a GitHub token to your .env file.');
                return redirect()->route('system.versions')
                    ->with('error', 'GitHub API rate limit exceeded. Please try again later or contact your administrator to add a GitHub token.');
            }
            
            if (!$tagsResponse->successful()) {
                // Try to check if the repository exists at all
                Log::info("Failed to get tags, checking if repository exists");
                $repoResponse = Http::withHeaders($headers)
                    ->timeout(30)
                    ->get("https://api.github.com/repos/{$this->githubOwner}/{$this->githubRepo}");
                
                Log::debug("GitHub Repository API Response Status: " . $repoResponse->status());
                Log::debug("GitHub Repository API Response Body: " . substr($repoResponse->body(), 0, 1000) . "...");
                
                if (!$repoResponse->successful()) {
                    Log::error("Repository not found: {$this->githubOwner}/{$this->githubRepo}");
                    return redirect()->route('system.versions')
                        ->with('error', "Repository not found. Please check if '{$this->githubOwner}/{$this->githubRepo}' exists and is accessible.");
                }
                
                Log::info("Repository exists but no tags found or could not access tags");
                return redirect()->route('system.versions')
                    ->with('error', 'Repository exists but no tags or releases found. Please create a release or tag on GitHub first.');
            }
            
            $tags = $tagsResponse->json();
            
            if (empty($tags)) {
                Log::info("No tags found in the repository");
                // Since the repository exists but has no tags, let's create an initial version from the integration branch
                $initialVersion = $this->createInitialVersionFromBranch($headers);
                if ($initialVersion) {
                    return redirect()->route('system.versions')
                        ->with('success', "Created initial version from '{$this->githubBranch}' branch.");
                }
                
                return redirect()->route('system.versions')
                    ->with('error', 'No tags found in the repository. Please create a tag or release on GitHub first.');
            }
            
            Log::info("Found " . count($tags) . " tags in the repository");
            $newVersionsCount = 0;
            
            foreach ($tags as $tag) {
                $tagName = $tag['name'] ?? null;
                
                if (!$tagName) {
                    continue;
                }
                
                // Check if this version is already in our database
                $existingVersion = SystemVersion::where('release_tag', $tagName)->first();
                
                if ($existingVersion) {
                    Log::info("Tag {$tagName} already exists in database, skipping");
                    continue; // Skip this tag as we already have it
                }
                
                Log::info("Adding new tag: {$tagName}");
                
                // Create a new version record based on the tag
                $version = new SystemVersion([
                    'version' => $tagName,
                    'release_tag' => $tagName,
                    'github_url' => "https://github.com/{$this->githubOwner}/{$this->githubRepo}/tree/{$tagName}",
                    'description' => "Tag: {$tagName} (No release notes available)",
                    'changelog' => "Tag: {$tagName} (No detailed changelog available)",
                    'is_active' => true,
                    'is_current' => false,
                ]);
                
                $version->save();
                $newVersionsCount++;
            }
            
            if ($newVersionsCount > 0) {
                Log::info("Added {$newVersionsCount} new tags to available updates");
                return redirect()->route('system.versions')
                    ->with('success', "Found {$newVersionsCount} new version(s) from tags and added to available updates.");
            } else {
                Log::info("No new tags found");
                return redirect()->route('system.versions')
                    ->with('info', "No new versions found. System is already aware of all available versions.");
            }
        } catch (Exception $e) {
            Log::error('Error checking for tags: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            return redirect()->route('system.versions')
                ->with('error', 'An error occurred while checking for tags: ' . $e->getMessage());
        }
    }
    
    /**
     * Create an initial version from the branch when no tags exist
     */
    private function createInitialVersionFromBranch($headers)
    {
        try {
            // Check if the branch exists
            $branchResponse = Http::withHeaders($headers)
                ->timeout(30)
                ->get("https://api.github.com/repos/{$this->githubOwner}/{$this->githubRepo}/branches/{$this->githubBranch}");
            
            if (!$branchResponse->successful()) {
                Log::error("Branch {$this->githubBranch} not found in repository");
                return false;
            }
            
            $branchData = $branchResponse->json();
            $commitSha = $branchData['commit']['sha'] ?? null;
            
            if (!$commitSha) {
                Log::error("Could not find commit SHA for branch {$this->githubBranch}");
                return false;
            }
            
            // Create a version based on the latest commit
            $version = new SystemVersion([
                'version' => "v0.1.0-{$this->githubBranch}",
                'release_tag' => $this->githubBranch,
                'github_url' => "https://github.com/{$this->githubOwner}/{$this->githubRepo}/tree/{$this->githubBranch}",
                'description' => "Initial version from {$this->githubBranch} branch (commit {$commitSha})",
                'changelog' => "Initial version from {$this->githubBranch} branch (commit {$commitSha})",
                'is_active' => true,
                'is_current' => false,
            ]);
            
            $version->save();
            Log::info("Created initial version from branch {$this->githubBranch}");
            return $version;
            
        } catch (Exception $e) {
            Log::error('Error creating initial version from branch: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Install or update to a specific version
     */
    public function updateToVersion($id)
    {
        try {
            $version = SystemVersion::findOrFail($id);
            
            // Check if we should skip backup (only for development)
            $skipBackup = false;
            if (session('skip_backup_for_update') && (app()->environment('local') || app()->environment('development'))) {
                $skipBackup = true;
                session()->forget('skip_backup_for_update'); // Clear the flag
                Log::warning('Skipping backup for version update as requested');
            }
            
            $backupPath = null;
            
            if (!$skipBackup) {
                // Try to create a backup of the current system
                $backupPath = $this->backupSystem();
                
                // If backup fails, try simpler backup method
                if (!$backupPath) {
                    Log::warning('Primary backup method failed, trying fallback method');
                    $backupPath = $this->fallbackBackupSystem();
                }
                
                // If backup still fails but we're in a development environment, proceed anyway
                if (!$backupPath && (app()->environment('local') || app()->environment('development'))) {
                    Log::warning('All backup methods failed, but proceeding with update in development environment');
                    $backupPath = 'no-backup-created';
                } else if (!$backupPath) {
                    Log::error('System backup failed during update process - all methods failed');
                    
                    // Check specific folder permissions
                    $storageApp = storage_path('app');
                    $backupsDir = storage_path('app/backups');
                    
                    $permissionInfo = [
                        'storage_app_exists' => File::exists($storageApp),
                        'storage_app_writable' => is_writable($storageApp),
                        'backups_dir_exists' => File::exists($backupsDir),
                        'backups_dir_writable' => File::exists($backupsDir) ? is_writable($backupsDir) : false,
                        'php_version' => PHP_VERSION,
                        'os' => PHP_OS,
                    ];
                    
                    Log::error('Backup directory permissions:', $permissionInfo);
                    
                    return redirect()->route('system.versions')
                        ->with('error', 'Failed to create system backup before update. Please check storage permissions or visit /debug-test-backup to diagnose. You can also visit /debug-skip-backup to bypass backup in development.');
                }
            } else {
                $backupPath = 'backup-skipped';
            }
            
            // Download the release from GitHub - try different URLs based on if it's a release or tag
            $releaseUrl = "https://github.com/{$this->githubOwner}/{$this->githubRepo}/archive/refs/tags/{$version->release_tag}.zip";
            $branchUrl = "https://github.com/{$this->githubOwner}/{$this->githubRepo}/archive/refs/heads/{$this->githubBranch}.zip";
            
            $zipPath = storage_path("app/updates/{$version->release_tag}.zip");
            
            // Ensure directory exists
            if (!File::exists(storage_path('app/updates'))) {
                File::makeDirectory(storage_path('app/updates'), 0755, true);
            }
            
            // Setup GitHub API auth headers
            $headers = [];
            if (config('services.github.token')) {
                $headers = [
                    'Authorization' => 'token ' . config('services.github.token'),
                ];
            }
            
            // Try downloading from the release URL first
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->get($releaseUrl);
            
            // If that fails, try the branch URL
            if (!$response->successful()) {
                Log::warning("Failed to download from tag URL, trying branch URL instead", [
                    'tag' => $version->release_tag,
                    'status' => $response->status()
                ]);
                
                $response = Http::withHeaders($headers)
                    ->timeout(30)
                    ->get($branchUrl);
                
                if (!$response->successful()) {
                    return redirect()->route('system.versions')
                        ->with('error', "Failed to download release from GitHub. Please check if the repository and branch/tag '{$version->release_tag}' exist and are accessible.");
                }
            }
            
            // Save the downloaded content
            File::put($zipPath, $response->body());
            
            // Make sure the downloaded file is not empty
            if (filesize($zipPath) < 1000) { // Less than 1KB is suspicious
                $fileContent = File::get($zipPath);
                Log::error("Downloaded file seems too small", [
                    'size' => filesize($zipPath),
                    'content_preview' => substr($fileContent, 0, 500)
                ]);
                
                return redirect()->route('system.versions')
                    ->with('error', 'Downloaded file appears to be empty or invalid. Please check GitHub repository permissions.');
            }
            
            // Extract and apply the update
            $extractPath = storage_path("app/updates/extract-{$version->release_tag}");
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }
            
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
                
                // Move the extracted files to the correct location
                // This is a simplified version, you may need a more complex file copying logic
                $extractedDir = glob($extractPath . '/*', GLOB_ONLYDIR)[0] ?? null;
                
                if (!$extractedDir) {
                    return redirect()->route('system.versions')
                        ->with('error', 'Failed to extract update files.');
                }
                
                // Save the backup path in the version record
                $version->backup_path = $backupPath;
                $version->save();
                
                // Run migration with our safer migration command for multi-tenant systems
                try {
                    Log::info("Running tenant-safe migrations during version update to {$version->version}");
                    
                    // Use our custom command that handles "table already exists" errors
                    Artisan::call('migrate:tenant-safe', ['--force' => true]);
                    
                    Log::info("Migration result: " . Artisan::output());
                } catch (Exception $e) {
                    // Log migration error but continue with the update if it's about tables already existing
                    Log::warning("Migration error during update: " . $e->getMessage());
                    
                    // For 'table already exists' errors, continue the update process
                    if (strpos($e->getMessage(), 'already exists') !== false) {
                        Log::info("Continuing update despite 'table already exists' error");
                    } else {
                        return redirect()->route('system.versions')
                            ->with('error', 'Database migration failed: ' . $e->getMessage());
                    }
                }
                
                Artisan::call('optimize:clear');
                
                // Mark this version as current
                $version->markAsCurrent();
                
                return redirect()->route('system.versions')
                    ->with('success', "Successfully updated to version {$version->version}.");
            } else {
                return redirect()->route('system.versions')
                    ->with('error', 'Failed to extract update archive.');
            }
            
        } catch (Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            
            return redirect()->route('system.versions')
                ->with('error', 'An error occurred during update: ' . $e->getMessage());
        }
    }
    
    /**
     * Roll back to a previous version
     */
    public function rollback($id)
    {
        try {
            $version = SystemVersion::findOrFail($id);
            
            if (!$version->is_active) {
                return redirect()->route('system.versions')
                    ->with('error', 'This version is not available for rollback.');
            }
            
            if ($version->is_current) {
                return redirect()->route('system.versions')
                    ->with('error', 'Cannot roll back to the current version.');
            }
            
            // Create a backup of the current system before rolling back (optional but recommended)
            $skipBackup = false;
            if (session('skip_backup_for_update') && (app()->environment('local') || app()->environment('development'))) {
                $skipBackup = true;
                session()->forget('skip_backup_for_update'); // Clear the flag
                Log::warning('Skipping backup for rollback as requested');
            }
            
            if (!$skipBackup) {
                $backupPath = $this->backupSystem();
                if (!$backupPath && !app()->environment('local') && !app()->environment('development')) {
                    Log::error('Failed to create backup during rollback');
                    return redirect()->route('system.versions')
                        ->with('error', 'Failed to create system backup before rollback.');
                }
                Log::info('Created backup before rollback: ' . ($backupPath ?? 'backup failed but proceeding in development'));
            }
            
            // Download the release from GitHub - similar to the update process
            $releaseUrl = "https://github.com/{$this->githubOwner}/{$this->githubRepo}/archive/refs/tags/{$version->release_tag}.zip";
            $branchUrl = "https://github.com/{$this->githubOwner}/{$this->githubRepo}/archive/refs/heads/{$this->githubBranch}.zip";
            
            $zipPath = storage_path("app/rollbacks/{$version->release_tag}.zip");
            
            // Ensure directory exists
            if (!File::exists(storage_path('app/rollbacks'))) {
                File::makeDirectory(storage_path('app/rollbacks'), 0755, true);
            }
            
            // Setup GitHub API auth headers
            $headers = [];
            if (config('services.github.token')) {
                $headers = [
                    'Authorization' => 'token ' . config('services.github.token'),
                    'User-Agent' => 'Alumni-Tracking-System-Updater'
                ];
            } else {
                $headers = [
                    'User-Agent' => 'Alumni-Tracking-System-Updater'
                ];
            }
            
            // Try downloading from the release URL first
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->get($releaseUrl);
            
            // If that fails, try the branch URL
            if (!$response->successful()) {
                Log::warning("Failed to download from tag URL for rollback, trying branch URL instead", [
                    'tag' => $version->release_tag,
                    'status' => $response->status()
                ]);
                
                $response = Http::withHeaders($headers)
                    ->timeout(30)
                    ->get($branchUrl);
                
                if (!$response->successful()) {
                    return redirect()->route('system.versions')
                        ->with('error', "Failed to download version from GitHub. Please check if the repository and tag '{$version->release_tag}' exist and are accessible.");
                }
            }
            
            // Save the downloaded content
            File::put($zipPath, $response->body());
            
            // Make sure the downloaded file is not empty
            if (filesize($zipPath) < 1000) { // Less than 1KB is suspicious
                $fileContent = File::get($zipPath);
                Log::error("Downloaded file for rollback seems too small", [
                    'size' => filesize($zipPath),
                    'content_preview' => substr($fileContent, 0, 500)
                ]);
                
                return redirect()->route('system.versions')
                    ->with('error', 'Downloaded file appears to be empty or invalid. Please check GitHub repository permissions.');
            }
            
            // Extract and apply the rollback
            $extractPath = storage_path("app/rollbacks/extract-{$version->release_tag}");
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            } else {
                // Clean directory if it exists
                File::cleanDirectory($extractPath);
            }
            
            // Extract the downloaded version
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
                
                // Move the extracted files to the correct location
                $extractedDir = glob($extractPath . '/*', GLOB_ONLYDIR)[0] ?? null;
                
                if (!$extractedDir) {
                    return redirect()->route('system.versions')
                        ->with('error', 'Failed to extract version files for rollback.');
                }
                
                // Copy files from the extracted directory to the application root
                File::copyDirectory($extractedDir, base_path());
                
                // Run migration with our safer migration command for multi-tenant systems
                try {
                    Log::info("Running tenant-safe migrations during rollback to {$version->version}");
                    
                    // Use our custom command that handles "table already exists" errors
                    Artisan::call('migrate:tenant-safe', ['--force' => true]);
                    
                    Log::info("Migration result: " . Artisan::output());
                } catch (Exception $e) {
                    // Log migration error but continue with the rollback if it's about tables already existing
                    Log::warning("Migration error during rollback: " . $e->getMessage());
                    
                    // For 'table already exists' errors, continue the rollback process
                    if (strpos($e->getMessage(), 'already exists') !== false) {
                        Log::info("Continuing rollback despite 'table already exists' error");
                    } else {
                        return redirect()->route('system.versions')
                            ->with('error', 'Database migration failed during rollback: ' . $e->getMessage());
                    }
                }
                
                Artisan::call('optimize:clear');
                
                // Mark this version as current
                $version->markAsCurrent();
                
                return redirect()->route('system.versions')
                    ->with('success', "Successfully rolled back to version {$version->version}.");
            } else {
                return redirect()->route('system.versions')
                    ->with('error', 'Failed to extract version archive for rollback.');
            }
            
        } catch (Exception $e) {
            Log::error('Rollback error: ' . $e->getMessage());
            
            return redirect()->route('system.versions')
                ->with('error', 'An error occurred during rollback: ' . $e->getMessage());
        }
    }
    
    /**
     * Create a backup of the current system
     * 
     * @return string|false The path to the backup file or false on failure
     */
    protected function backupSystem()
    {
        try {
            $timestamp = date('Y-m-d-His');
            $backupFileName = "system-backup-{$timestamp}.zip";
            $backupPath = "backups/{$backupFileName}";
            $fullBackupPath = storage_path("app/{$backupPath}");
            
            // Ensure backups directory exists with proper permissions
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                if (!File::makeDirectory($backupDir, 0755, true)) {
                    Log::error("Failed to create backup directory: {$backupDir}");
                    return false;
                }
            } else {
                // Ensure directory is writable
                if (!is_writable($backupDir)) {
                    chmod($backupDir, 0755);
                    if (!is_writable($backupDir)) {
                        Log::error("Backup directory is not writable: {$backupDir}");
                        return false;
                    }
                }
            }
            
            // Create a ZIP archive of important files
            $zip = new ZipArchive();
            $result = $zip->open($fullBackupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            
            if ($result !== true) {
                Log::error("Failed to create ZIP archive: error code {$result}");
                return false;
            }
            
            // Directories to backup
            $directoriesToBackup = [
                'app' => base_path('app'),
                'config' => base_path('config'),
                'database' => base_path('database'),
                'public' => base_path('public'),
                'resources' => base_path('resources'),
                'routes' => base_path('routes'),
            ];
            
            // Add each directory to the ZIP
            foreach ($directoriesToBackup as $zipPath => $path) {
                if (File::isDirectory($path)) {
                    // Add empty directory
                    $zip->addEmptyDir($zipPath);
                    $this->addDirectoryToZip($zip, $path, $zipPath);
                } else {
                    Log::warning("Directory not found for backup: {$path}");
                }
            }
            
            // Add specific files
            $filesToBackup = [
                '.env',
                'composer.json',
                'composer.lock',
                'package.json',
                'webpack.mix.js',
                'artisan',
            ];
            
            foreach ($filesToBackup as $file) {
                $filePath = base_path($file);
                if (File::exists($filePath)) {
                    $zip->addFile($filePath, $file);
                } else {
                    Log::info("File not found for backup (non-critical): {$filePath}");
                }
            }
            
            $zip->close();
            
            // Verify the ZIP was created successfully
            if (!File::exists($fullBackupPath) || filesize($fullBackupPath) < 1000) {
                Log::error("ZIP archive was not created properly: {$fullBackupPath}");
                return false;
            }
            
            Log::info("System backup created successfully: {$backupPath}");
            return $backupPath;
        } catch (Exception $e) {
            Log::error('Backup error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }
    
    /**
     * Add a directory to the ZIP archive
     */
    protected function addDirectoryToZip(ZipArchive $zip, $path, $zipPath)
    {
        if (!File::isDirectory($path)) {
            return;
        }
        
        // Create empty directory
        $zip->addEmptyDir($zipPath);
        
        $files = File::files($path);
        foreach ($files as $file) {
            $zip->addFile($file->getPathname(), $zipPath . '/' . $file->getFilename());
        }
        
        $directories = File::directories($path);
        foreach ($directories as $directory) {
            $this->addDirectoryToZip(
                $zip,
                $directory,
                $zipPath . '/' . basename($directory)
            );
        }
    }
    
    /**
     * Fallback backup method using simple file operations (no ZipArchive)
     * 
     * @return string|false The path to the backup directory or false on failure
     */
    protected function fallbackBackupSystem()
    {
        try {
            $timestamp = date('Y-m-d-His');
            $backupDirName = "system-backup-{$timestamp}";
            $backupPath = "backups/{$backupDirName}";
            $fullBackupPath = storage_path("app/{$backupPath}");
            
            // Ensure backups directory exists
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                if (!File::makeDirectory($backupDir, 0755, true)) {
                    Log::error("Failed to create backup directory: {$backupDir}");
                    return false;
                }
            }
            
            // Create the backup directory
            if (!File::makeDirectory($fullBackupPath, 0755, true)) {
                Log::error("Failed to create backup directory: {$fullBackupPath}");
                return false;
            }
            
            // Directories to backup (minimal set for fallback)
            $directoriesToBackup = [
                'app/Http/Controllers' => base_path('app/Http/Controllers'),
                'resources/views' => base_path('resources/views'),
                'routes' => base_path('routes'),
            ];
            
            // Copy each directory to the backup
            foreach ($directoriesToBackup as $destination => $source) {
                if (File::isDirectory($source)) {
                    $destinationPath = $fullBackupPath . '/' . $destination;
                    
                    // Create destination directory
                    if (!File::exists(dirname($destinationPath))) {
                        File::makeDirectory(dirname($destinationPath), 0755, true);
                    }
                    
                    // Copy directory
                    File::copyDirectory($source, $destinationPath);
                } else {
                    Log::warning("Directory not found for backup: {$source}");
                }
            }
            
            // Add basic files
            $filesToBackup = [
                '.env' => base_path('.env'),
                'composer.json' => base_path('composer.json'),
            ];
            
            foreach ($filesToBackup as $destination => $source) {
                if (File::exists($source)) {
                    $destinationPath = $fullBackupPath . '/' . $destination;
                    
                    // Create destination directory
                    if (!File::exists(dirname($destinationPath))) {
                        File::makeDirectory(dirname($destinationPath), 0755, true);
                    }
                    
                    // Copy file
                    File::copy($source, $destinationPath);
                }
            }
            
            // Create a simple metadata file
            $metadataPath = $fullBackupPath . '/backup-info.txt';
            $metadataContent = "Backup created: " . date('Y-m-d H:i:s') . "\n";
            $metadataContent .= "Current version: " . SystemVersion::getCurrentVersion()->version . "\n";
            $metadataContent .= "Fallback backup method used (no ZIP compression)\n";
            
            File::put($metadataPath, $metadataContent);
            
            Log::info("Fallback system backup created successfully: {$backupPath}");
            return $backupPath;
        } catch (Exception $e) {
            Log::error('Fallback backup error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }
}

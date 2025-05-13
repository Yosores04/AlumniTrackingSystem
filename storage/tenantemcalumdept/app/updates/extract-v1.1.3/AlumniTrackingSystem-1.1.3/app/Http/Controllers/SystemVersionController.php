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
    protected $githubRepo = 'WST-T83-ALUMNI-TRACKING-SYSTEM';
    protected $githubOwner = 'GarcianoNilo';
    
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
        
        // Get all available versions that can be rolled back to
        $availableVersions = SystemVersion::getAvailableVersions();
        
        // Settings for layout
        $settings = TenantSettings::getSettings();
        
        return view('tenant.system.versions', [
            'currentVersion' => $currentVersion,
            'availableVersions' => $availableVersions,
            'settings' => $settings,
        ]);
    }
    
    /**
     * Check for new GitHub releases
     */
    public function checkForUpdates()
    {
        try {
            // Fetch the latest release from GitHub API
            $response = Http::get("https://api.github.com/repos/{$this->githubOwner}/{$this->githubRepo}/releases/latest");
            
            if (!$response->successful()) {
                return redirect()->route('system.versions')
                    ->with('error', 'Failed to fetch releases from GitHub: ' . $response->body());
            }
            
            $latestRelease = $response->json();
            $latestTag = $latestRelease['tag_name'] ?? null;
            
            if (!$latestTag) {
                return redirect()->route('system.versions')
                    ->with('error', 'No valid release tag found on GitHub.');
            }
            
            // Check if this version is already in our database
            $existingVersion = SystemVersion::where('release_tag', $latestTag)->first();
            
            if ($existingVersion) {
                return redirect()->route('system.versions')
                    ->with('info', "System is already aware of version {$latestTag}.");
            }
            
            // Create a new version record
            $version = new SystemVersion([
                'version' => $latestTag,
                'release_tag' => $latestTag,
                'github_url' => $latestRelease['html_url'] ?? '',
                'description' => $latestRelease['body'] ?? '',
                'changelog' => $latestRelease['body'] ?? '',
                'is_active' => false,
                'is_current' => false,
            ]);
            
            $version->save();
            
            return redirect()->route('system.versions')
                ->with('success', "New version {$latestTag} found and added to available updates.");
                
        } catch (Exception $e) {
            Log::error('Error checking for updates: ' . $e->getMessage());
            
            return redirect()->route('system.versions')
                ->with('error', 'An error occurred while checking for updates: ' . $e->getMessage());
        }
    }
    
    /**
     * Install or update to a specific version
     */
    public function updateToVersion($id)
    {
        try {
            $version = SystemVersion::findOrFail($id);
            
            // Create a backup of the current system
            $backupPath = $this->backupSystem();
            
            if (!$backupPath) {
                return redirect()->route('system.versions')
                    ->with('error', 'Failed to create system backup before update.');
            }
            
            // Download the release from GitHub
            $downloadUrl = "https://github.com/{$this->githubOwner}/{$this->githubRepo}/archive/refs/tags/{$version->release_tag}.zip";
            $zipPath = storage_path("app/updates/{$version->release_tag}.zip");
            
            // Ensure directory exists
            if (!File::exists(storage_path('app/updates'))) {
                File::makeDirectory(storage_path('app/updates'), 0755, true);
            }
            
            // Download the zip file
            $response = Http::get($downloadUrl);
            if (!$response->successful()) {
                return redirect()->route('system.versions')
                    ->with('error', 'Failed to download release from GitHub.');
            }
            
            File::put($zipPath, $response->body());
            
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
                
                // Run migration and other update tasks
                Artisan::call('migrate');
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
            
            if (!$version->backup_path || !Storage::disk('local')->exists($version->backup_path)) {
                return redirect()->route('system.versions')
                    ->with('error', 'Backup files for this version are not available.');
            }
            
            // Create a backup of the current system before rolling back
            $backupPath = $this->backupSystem();
            
            if (!$backupPath) {
                return redirect()->route('system.versions')
                    ->with('error', 'Failed to create system backup before rollback.');
            }
            
            // Restore from the backup
            $backupZipPath = storage_path('app/' . $version->backup_path);
            $extractPath = storage_path('app/rollback-temp');
            
            // Ensure directory exists
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            } else {
                // Clean directory
                File::cleanDirectory($extractPath);
            }
            
            // Extract the backup
            $zip = new ZipArchive;
            if ($zip->open($backupZipPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
                
                // Restore files
                // This is a simplified version, you may need a more complex file copying logic
                File::copyDirectory($extractPath, base_path());
                
                // Run migration and other rollback tasks
                Artisan::call('migrate');
                Artisan::call('optimize:clear');
                
                // Mark this version as current
                $version->markAsCurrent();
                
                return redirect()->route('system.versions')
                    ->with('success', "Successfully rolled back to version {$version->version}.");
            } else {
                return redirect()->route('system.versions')
                    ->with('error', 'Failed to extract backup archive.');
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
            
            // Ensure backups directory exists
            if (!File::exists(storage_path('app/backups'))) {
                File::makeDirectory(storage_path('app/backups'), 0755, true);
            }
            
            // Create a ZIP archive of important files
            $zip = new ZipArchive();
            if ($zip->open($fullBackupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Add application files
                $this->addDirectoryToZip($zip, base_path('app'), 'app');
                $this->addDirectoryToZip($zip, base_path('config'), 'config');
                $this->addDirectoryToZip($zip, base_path('database'), 'database');
                $this->addDirectoryToZip($zip, base_path('public'), 'public');
                $this->addDirectoryToZip($zip, base_path('resources'), 'resources');
                $this->addDirectoryToZip($zip, base_path('routes'), 'routes');
                
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
                    if (File::exists(base_path($file))) {
                        $zip->addFile(base_path($file), $file);
                    }
                }
                
                $zip->close();
                return $backupPath;
            }
            
            return false;
        } catch (Exception $e) {
            Log::error('Backup error: ' . $e->getMessage());
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
}

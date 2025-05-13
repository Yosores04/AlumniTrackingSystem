<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class SetTenantCurrentVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:set-current-version {tenant? : Specific tenant to update} {--version= : Version to set as current (e.g. v1.1.2)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the current version for tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificTenant = $this->argument('tenant');
        $version = $this->option('version');
        
        if (!$version) {
            // Default to the latest version from GitHub
            $controller = new \App\Http\Controllers\SystemVersionController();
            $githubOwner = $controller->githubOwner;
            $githubRepo = $controller->githubRepo;
            
            $this->info("No version specified, trying to detect latest version from GitHub ({$githubOwner}/{$githubRepo})...");
            
            // Setup GitHub API headers
            $headers = ['User-Agent' => 'Alumni-Tracking-System-Updater'];
            
            try {
                // Check releases first
                $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                    ->timeout(10)
                    ->get("https://api.github.com/repos/{$githubOwner}/{$githubRepo}/releases");
                
                if ($response->successful() && !empty($response->json())) {
                    $releases = $response->json();
                    $version = $releases[0]['tag_name'] ?? null;
                    $this->info("Detected latest release version: {$version}");
                } else {
                    // If no releases, check tags
                    $tagsResponse = \Illuminate\Support\Facades\Http::withHeaders($headers)
                        ->timeout(10)
                        ->get("https://api.github.com/repos/{$githubOwner}/{$githubRepo}/tags");
                    
                    if ($tagsResponse->successful() && !empty($tagsResponse->json())) {
                        $tags = $tagsResponse->json();
                        $version = $tags[0]['name'] ?? null;
                        $this->info("Detected latest tag version: {$version}");
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error detecting version: {$e->getMessage()}");
            }
            
            if (!$version) {
                $version = 'v1.1.2'; // Default fallback
                $this->warn("Could not detect version, using default: {$version}");
            }
            
            if (!$this->confirm("Set current version to {$version}?", true)) {
                $version = $this->ask('Enter the version to set as current (e.g. v1.1.2)');
            }
        }
        
        if (empty($version)) {
            $this->error('No version specified. Use --version option or allow auto-detection.');
            return Command::FAILURE;
        }
        
        if ($specificTenant) {
            $tenants = Tenant::where('id', $specificTenant)->get();
        } else {
            $tenants = Tenant::all();
        }
        
        if ($tenants->isEmpty()) {
            $this->error('No tenants found');
            return Command::FAILURE;
        }
        
        $this->info('Setting current version to ' . $version . ' for ' . $tenants->count() . ' tenant(s)...');
        
        $errors = 0;
        $success = 0;
        $skipped = 0;
        
        foreach ($tenants as $tenant) {
            $this->line("\nProcessing tenant: {$tenant->id}");
            
            try {
                // Initialize tenant
                tenancy()->initialize($tenant);
                
                // Check if system_versions table exists
                $tableExists = false;
                try {
                    DB::table('system_versions')->limit(1)->get();
                    $tableExists = true;
                } catch (\Exception $e) {
                    // Table doesn't exist
                }
                
                if (!$tableExists) {
                    $this->warn("  ❌ system_versions table does not exist, creating it...");
                    
                    // Create the table if it doesn't exist
                    \Illuminate\Support\Facades\Schema::create('system_versions', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->id();
                        $table->string('version');
                        $table->string('release_tag');
                        $table->string('github_url');
                        $table->text('description')->nullable();
                        $table->text('changelog')->nullable();
                        $table->text('backup_path')->nullable();
                        $table->boolean('is_active')->default(false);
                        $table->boolean('is_current')->default(false);
                        $table->dateTime('installed_at')->nullable();
                        $table->timestamps();
                    });
                    
                    $this->info("  ✅ system_versions table created");
                }
                
                // Check if the version exists
                $versionExists = DB::table('system_versions')
                    ->where('version', $version)
                    ->exists();
                
                if (!$versionExists) {
                    $this->warn("  ⚠️ Version {$version} does not exist, creating it...");
                    
                    // Create the version record
                    DB::table('system_versions')->insert([
                        'version' => $version,
                        'release_tag' => $version,
                        'github_url' => "https://github.com/{$githubOwner}/{$githubRepo}/releases/tag/{$version}",
                        'description' => "Version {$version}",
                        'changelog' => "Version {$version}",
                        'is_active' => true,
                        'is_current' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->info("  ✅ Version {$version} created");
                }
                
                // Get count of existing versions that are marked as current
                $currentCount = DB::table('system_versions')
                    ->where('is_current', true)
                    ->count();
                
                if ($currentCount > 0) {
                    $this->line("  - Unmarking current version(s)...");
                    
                    // Unmark all current versions
                    DB::table('system_versions')
                        ->where('is_current', true)
                        ->update([
                            'is_current' => false,
                            'updated_at' => now(),
                        ]);
                }
                
                // Mark the specified version as current
                $updated = DB::table('system_versions')
                    ->where('version', $version)
                    ->update([
                        'is_current' => true,
                        'is_active' => true,
                        'installed_at' => now(),
                        'updated_at' => now(),
                    ]);
                
                if ($updated) {
                    $this->info("  ✅ Version {$version} set as current");
                    $success++;
                } else {
                    $this->error("  ❌ Failed to set version {$version} as current");
                    $errors++;
                }
                
            } catch (\Exception $e) {
                $this->error("  ❌ Error processing tenant: {$e->getMessage()}");
                $errors++;
            } finally {
                // End tenancy for this tenant
                tenancy()->end();
            }
        }
        
        $this->line("\nSummary:");
        $this->line("  - Successful: {$success}");
        $this->line("  - Errors: {$errors}");
        $this->line("  - Skipped: {$skipped}");
        
        if ($errors > 0) {
            return Command::FAILURE;
        }
        
        $this->info("\nDone. Run 'php artisan tenants:check-versions' to verify.");
        return Command::SUCCESS;
    }
} 
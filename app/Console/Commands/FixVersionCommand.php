<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class FixVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:versions {tenant? : Specific tenant to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes system_versions records in tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificTenant = $this->argument('tenant');
        
        if ($specificTenant) {
            $tenants = Tenant::where('id', $specificTenant)->get();
        } else {
            $tenants = Tenant::all();
        }
        
        if ($tenants->isEmpty()) {
            $this->error('No tenants found');
            return Command::FAILURE;
        }
        
        $this->info('Fixing system_versions for ' . $tenants->count() . ' tenant(s)...');
        
        $errors = 0;
        $success = 0;
        
        // Define all versions we want to add
        $allVersions = [
            [
                'version' => 'v1.0.0',
                'release_tag' => 'v1.0.0',
                'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/releases/tag/v1.0.0',
                'description' => '### Key Features - **Multi-tenancy Architecture**: Support for multiple departments/institutions...',
                'changelog' => 'Initial release with multi-tenancy support',
                'is_active' => true,
                'is_current' => false,
            ],
            [
                'version' => 'v1.1.0',
                'release_tag' => 'v1.1.0',
                'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/releases/tag/v1.1.0',
                'description' => '### Fixed some missing files',
                'changelog' => 'Fixed various missing files and dependencies',
                'is_active' => true,
                'is_current' => false,
            ],
            [
                'version' => 'v1.1.1',
                'release_tag' => 'v1.1.1',
                'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/releases/tag/v1.1.1',
                'description' => 'test',
                'changelog' => 'test release',
                'is_active' => true,
                'is_current' => false,
            ],
            [
                'version' => 'v1.1.2',
                'release_tag' => 'v1.1.2',
                'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/releases/tag/v1.1.2',
                'description' => 'Version v1.1.2',
                'changelog' => 'Version v1.1.2',
                'is_active' => true,
                'is_current' => true,
            ],
        ];
        
        foreach ($tenants as $tenant) {
            $this->line("\nProcessing tenant: {$tenant->id}");
            
            try {
                // Initialize tenant
                tenancy()->initialize($tenant);
                
                // Check if system_versions table exists
                if (!Schema::hasTable('system_versions')) {
                    $this->warn("  ❌ system_versions table does not exist, creating it...");
                    
                    // Create the table if it doesn't exist
                    Schema::create('system_versions', function ($table) {
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
                
                // Clear all existing records
                DB::table('system_versions')->delete();
                $this->info("  ✅ Cleared existing version records");
                
                // Add all versions
                foreach ($allVersions as $versionData) {
                    // Add installed_at for the current version
                    if ($versionData['is_current']) {
                        $versionData['installed_at'] = now();
                    }
                    
                    // Add timestamps
                    $versionData['created_at'] = now();
                    $versionData['updated_at'] = now();
                    
                    DB::table('system_versions')->insert($versionData);
                }
                
                $this->info("  ✅ Added all version records with v1.1.2 set as current");
                $success++;
                
            } catch (\Exception $e) {
                $this->error("  ❌ Error processing tenant: {$e->getMessage()}");
                Log::error("Fix versions error for tenant {$tenant->id}: " . $e->getMessage());
                $errors++;
            } finally {
                // End tenancy for this tenant
                tenancy()->end();
            }
        }
        
        $this->line("\nSummary:");
        $this->line("  - Successful: {$success}");
        $this->line("  - Errors: {$errors}");
        
        if ($errors > 0) {
            return Command::FAILURE;
        }
        
        $this->info("\nDone. Run 'php artisan tenants:check-versions' to verify.");
        return Command::SUCCESS;
    }
} 
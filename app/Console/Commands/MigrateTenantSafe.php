<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class MigrateTenantSafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant-safe {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations safely in a multi-tenant environment, ignoring "table already exists" errors';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running tenant-safe migrations...');
        
        $force = $this->option('force');
        
        try {
            // First attempt a normal migration
            $this->call('migrate', [
                '--force' => $force,
            ]);
            
            $this->info('Migrations completed successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->warn('Error during migration: ' . $e->getMessage());
            
            // Check if it's a "table already exists" error
            if (strpos($e->getMessage(), 'already exists') !== false) {
                $this->info('Detected "table already exists" error. Continuing with remaining migrations...');
                
                // We'll try to run migrations on a per-file basis to skip the problematic ones
                $this->runRemainingMigrations($force);
                return 0;
            }
            
            $this->error('Migration failed: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Run remaining migrations by executing them individually
     */
    protected function runRemainingMigrations($force)
    {
        $migrationPath = database_path('migrations');
        $migrationFiles = glob($migrationPath . '/*.php');
        $count = 0;
        
        foreach ($migrationFiles as $file) {
            $baseName = basename($file, '.php');
            $className = 'Database\\Migrations\\' . $this->getClassNameFromFile($file);
            
            try {
                // Check if this migration has already been run
                $exists = DB::table('migrations')->where('migration', $baseName)->exists();
                
                if (!$exists) {
                    $this->info("Running migration: {$baseName}");
                    
                    // Include and execute the migration manually
                    require_once $file;
                    
                    if (class_exists($className)) {
                        $migration = new $className;
                        
                        try {
                            // Run the migration up method
                            $migration->up();
                            
                            // Mark migration as run
                            DB::table('migrations')->insert([
                                'migration' => $baseName,
                                'batch' => DB::table('migrations')->max('batch') + 1
                            ]);
                            
                            $count++;
                        } catch (QueryException $qe) {
                            // If it's a "table already exists" error, mark it as run and continue
                            if (strpos($qe->getMessage(), 'already exists') !== false) {
                                $this->warn("- Table already exists in {$baseName}, marking as completed");
                                
                                // Mark migration as run
                                DB::table('migrations')->insert([
                                    'migration' => $baseName,
                                    'batch' => DB::table('migrations')->max('batch') + 1
                                ]);
                                
                                $count++;
                            } else {
                                throw $qe;
                            }
                        }
                    } else {
                        $this->warn("- Could not find migration class in {$baseName}");
                    }
                }
            } catch (\Exception $e) {
                $this->error("- Failed to run {$baseName}: " . $e->getMessage());
                Log::error("Migration error for {$baseName}: " . $e->getMessage());
            }
        }
        
        $this->info("Completed {$count} remaining migrations.");
    }
    
    /**
     * Extract class name from migration file
     */
    protected function getClassNameFromFile($file)
    {
        $content = file_get_contents($file);
        $matches = [];
        preg_match('/class\s+(\w+)\s+extends/i', $content, $matches);
        
        return $matches[1] ?? '';
    }
} 
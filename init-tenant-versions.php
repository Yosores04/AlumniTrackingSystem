<?php

/**
 * Initialize system versions in tenant database
 * Run this after tenants:migrate-fresh to restore version data
 */

// Setup database connection
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the tenant database connection
$tenantDB = 'tenantupdatetest';
$conn = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'database' => $tenantDB,
    'username' => config('database.connections.mysql.username'),
    'password' => config('database.connections.mysql.password'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
];

// Set up the connection
config(['database.connections.tenant' => $conn]);
\Illuminate\Support\Facades\DB::purge('tenant');

try {
    $tenantConnection = \Illuminate\Support\Facades\DB::connection('tenant');
    echo "Connected to tenant database: $tenantDB\n";
    
    // Check if system_versions table exists
    $tableExists = $tenantConnection->getSchemaBuilder()->hasTable('system_versions');
    if (!$tableExists) {
        echo "Creating system_versions table...\n";
        $tenantConnection->getSchemaBuilder()->create('system_versions', function ($table) {
            $table->id();
            $table->string('version')->index();
            $table->string('release_tag')->index();
            $table->string('github_url')->nullable();
            $table->text('description')->nullable();
            $table->text('changelog')->nullable();
            $table->string('backup_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_current')->default(false);
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();
        });
    } else {
        echo "System_versions table exists, truncating it...\n";
        $tenantConnection->table('system_versions')->truncate();
    }
    
    // Insert all standard versions
    $versions = [
        [
            'version' => 'v1.0.0',
            'release_tag' => 'v1.0.0',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.0.0',
            'description' => 'Initial release',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.1.0',
            'release_tag' => 'v1.1.0',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.1.0',
            'description' => 'Minor update with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.1.1',
            'release_tag' => 'v1.1.1',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.1.1',
            'description' => 'Patch with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.1.2',
            'release_tag' => 'v1.1.2',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.1.2',
            'description' => 'Patch with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.1.3',
            'release_tag' => 'v1.1.3',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.1.3',
            'description' => 'Patch with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.1.4',
            'release_tag' => 'v1.1.4',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.1.4',
            'description' => 'Patch with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.1.5',
            'release_tag' => 'v1.1.5',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.1.5',
            'description' => 'Patch with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.2.1',
            'release_tag' => 'v1.2.1',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.2.1',
            'description' => 'Minor update with new features',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.2.2',
            'release_tag' => 'v1.2.2',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.2.2',
            'description' => 'Patch with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.3.1',
            'release_tag' => 'v1.3.1',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.3.1',
            'description' => 'Minor update with new features',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.4.1',
            'release_tag' => 'v1.4.1',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.4.1',
            'description' => 'Minor update with new features',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'version' => 'v1.4.2',
            'release_tag' => 'v1.4.2',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/v1.4.2',
            'description' => 'Patch with bug fixes',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        // Add integration version
        [
            'version' => 'integration-'.date('Ymd').'-current',
            'release_tag' => 'integration',
            'github_url' => 'https://github.com/Yosores04/AlumniTrackingSystem/tree/integration',
            'description' => 'Latest code from integration branch (as of '.date('Y-m-d H:i:s').')',
            'changelog' => 'Latest code from integration branch',
            'is_active' => true,
            'is_current' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];
    
    // Insert all versions
    foreach ($versions as $version) {
        $tenantConnection->table('system_versions')->insert($version);
    }
    
    echo "Inserted " . count($versions) . " versions into system_versions table\n";
    
    // Ensure v1.4.2 is marked as current (double-check)
    $currentVersionUpdated = $tenantConnection->table('system_versions')
        ->where('version', 'v1.4.2')
        ->update(['is_current' => true, 'is_active' => true, 'installed_at' => now()]);
        
    echo "Explicitly marked v1.4.2 as current: " . ($currentVersionUpdated ? "SUCCESS" : "FAILED") . "\n";
    
    // List all versions for verification
    $allVersions = $tenantConnection->table('system_versions')->get();
    echo "Database now has " . count($allVersions) . " versions\n";
    
    // Find and report current version
    $currentVersion = $tenantConnection->table('system_versions')->where('is_current', true)->first();
    echo "CURRENT VERSION: " . ($currentVersion ? $currentVersion->version : "NONE") . "\n";
    
    foreach ($allVersions as $ver) {
        echo "Version: {$ver->version}, Current: " . ($ver->is_current ? "YES" : "no") . ", Active: " . ($ver->is_active ? "YES" : "no") . "\n";
    }
    
    echo "\nDone! Your system_versions table has been initialized with all standard versions.\n";
    echo "v1.4.2 is set as the current version. You can now check the System Version Management page.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 
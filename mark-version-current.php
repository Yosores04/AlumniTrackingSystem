<?php

/**
 * Mark a specific version as current in a tenant database
 * 
 * Usage: php mark-version-current.php tenantupdatetest v1.4.2
 * Where:
 *   - tenantupdatetest is the tenant database name
 *   - v1.4.2 is the version to mark as current
 */

if ($argc < 3) {
    die("Usage: php mark-version-current.php {tenant_db} {version}\n");
}

$tenantDB = $argv[1];
$versionToMark = $argv[2];

// Setup database connection
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the tenant database connection
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
        die("Error: system_versions table does not exist in database $tenantDB\n");
    }
    
    // Check if the version exists
    $versionExists = $tenantConnection->table('system_versions')
        ->where('version', $versionToMark)
        ->exists();
        
    if (!$versionExists) {
        // Try to find similar versions
        $similarVersions = $tenantConnection->table('system_versions')
            ->pluck('version')
            ->toArray();
            
        echo "Error: Version '$versionToMark' does not exist in database.\n";
        echo "Available versions: " . implode(", ", $similarVersions) . "\n";
        die();
    }
    
    // First unmark all current versions
    $tenantConnection->table('system_versions')
        ->update(['is_current' => false]);
        
    // Mark the specified version as current
    $tenantConnection->table('system_versions')
        ->where('version', $versionToMark)
        ->update([
            'is_current' => true,
            'is_active' => true,
            'installed_at' => now()
        ]);
        
    echo "Successfully marked version '$versionToMark' as current.\n";
    
    // Verify the current version
    $currentVersion = $tenantConnection->table('system_versions')
        ->where('is_current', true)
        ->first();
        
    if ($currentVersion) {
        echo "Current version is now: {$currentVersion->version}\n";
    } else {
        echo "Warning: No current version found after update.\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 
<?php

/**
 * Simple script to set a version as the current version in a tenant's system_versions table
 * 
 * Usage: php fix-tenant-version.php
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

// Use the tenant connection
try {
    $tenantConnection = \Illuminate\Support\Facades\DB::connection('tenant');
    
    echo "Connected to tenant database: $tenantDB\n";
    
    // First, clear all current versions
    $tenantConnection->table('system_versions')->update(['is_current' => false]);
    echo "Cleared all current versions\n";
    
    // List all versions for debugging
    $allVersions = $tenantConnection->table('system_versions')->get();
    echo "Found " . count($allVersions) . " versions in database\n";
    
    foreach ($allVersions as $ver) {
        echo "Version: {$ver->version}, ID: {$ver->id}, Current: {$ver->is_current}, Active: {$ver->is_active}\n";
    }
    
    // Set v1.5.1 as the current version and activate it
    $updated = $tenantConnection->table('system_versions')
        ->where('version', 'v1.5.1')
        ->update([
            'is_current' => true,
            'is_active' => true,
            'installed_at' => now()
        ]);
    
    // If v1.5.1 doesn't exist, try v1.4.2
    if ($updated === 0) {
        echo "v1.5.1 not found, trying v1.4.2...\n";
        $updated = $tenantConnection->table('system_versions')
            ->where('version', 'v1.4.2')
            ->update([
                'is_current' => true,
                'is_active' => true,
                'installed_at' => now()
            ]);
    }
    
    // If that still fails, mark the newest version by ID
    if ($updated === 0) {
        echo "v1.4.2 not found, trying latest version by ID...\n";
        $latestVersion = $tenantConnection->table('system_versions')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($latestVersion) {
            $tenantConnection->table('system_versions')
                ->where('id', $latestVersion->id)
                ->update([
                    'is_current' => true,
                    'is_active' => true,
                    'installed_at' => now()
                ]);
            
            echo "Marked version {$latestVersion->version} (ID: {$latestVersion->id}) as current.\n";
        } else {
            echo "No versions found in the database.\n";
        }
    } else {
        echo "Updated {$updated} version record(s).\n";
    }
    
    // Also mark all versions as active
    $tenantConnection->table('system_versions')
        ->update(['is_active' => true]);
    
    echo "All versions marked as active.\n";
    echo "Done! You can now check the System Version Management page to see your current version.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 
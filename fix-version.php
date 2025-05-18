<?php

/**
 * Simple script to set a version as the current version in the system_versions table
 * 
 * Usage: php fix-version.php
 */

// Setup database connection
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// First, clear all current versions
DB::table('system_versions')->update(['is_current' => false]);

// Set v1.5.1 as the current version and activate it
$updated = DB::table('system_versions')
    ->where('version', 'v1.5.1')
    ->update([
        'is_current' => true,
        'is_active' => true,
        'installed_at' => now()
    ]);

// If v1.5.1 doesn't exist, try v1.4.2
if ($updated === 0) {
    $updated = DB::table('system_versions')
        ->where('version', 'v1.4.2')
        ->update([
            'is_current' => true,
            'is_active' => true,
            'installed_at' => now()
        ]);
}

// If that still fails, mark the newest version by ID
if ($updated === 0) {
    $latestVersion = DB::table('system_versions')
        ->orderBy('id', 'desc')
        ->first();
    
    if ($latestVersion) {
        DB::table('system_versions')
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
DB::table('system_versions')
    ->update(['is_active' => true]);

echo "All versions marked as active.\n";
echo "Done! You can now check the System Version Management page to see your current version.\n"; 
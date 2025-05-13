<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sync JSON data status with status column
        $tenants = DB::table('tenants')->get();
        $updated = 0;
        
        foreach ($tenants as $tenant) {
            try {
                // Get status from data JSON if it exists
                $data = json_decode($tenant->data ?? '{}', true) ?: [];
                $jsonStatus = $data['status'] ?? null;
                
                // Only update if JSON status exists and differs from column status
                if ($jsonStatus && $jsonStatus !== $tenant->status) {
                    DB::table('tenants')
                        ->where('id', $tenant->id)
                        ->update(['status' => $jsonStatus]);
                    
                    $updated++;
                    Log::info("Updated tenant status from JSON", [
                        'tenant_id' => $tenant->id,
                        'old_status' => $tenant->status,
                        'new_status' => $jsonStatus
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Failed to sync tenant status", [
                    'tenant_id' => $tenant->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::info("Status column sync complete. Updated $updated tenants.");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this as it's just syncing data
    }
};

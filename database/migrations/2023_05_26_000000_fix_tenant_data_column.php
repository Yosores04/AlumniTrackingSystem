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
        // First, modify the column to ensure it's properly set as JSON
        Schema::table('tenants', function (Blueprint $table) {
            // Make sure column exists first
            if (Schema::hasColumn('tenants', 'data')) {
                $table->json('data')->nullable()->change();
            } else {
                $table->json('data')->nullable();
            }
        });

        // Now, run direct SQL to update existing tenants with proper JSON
        $tenants = DB::table('tenants')->get();
        $fixed = 0;
        
        foreach ($tenants as $tenant) {
            // Set default data with status for each tenant
            $data = ['status' => 'active'];
            
            // Run direct SQL update to bypass any model issues
            DB::table('tenants')
                ->where('id', $tenant->id)
                ->update(['data' => json_encode($data)]);
                
            // Use Laravel's built-in logging instead
            Log::info("Fixed tenant data for {$tenant->id}");
            $fixed++;
        }
        
        Log::info("Fixed data for {$fixed} tenants");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration as it's a data fix
    }
};

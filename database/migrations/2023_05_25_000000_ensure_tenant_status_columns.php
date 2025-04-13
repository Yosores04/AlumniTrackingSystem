<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if tenants table exists
        if (!Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->timestamps();
                $table->json('data')->nullable();
                $table->json('subscription')->nullable();
            });
        } else {
            // Add data column if it doesn't exist
            if (!Schema::hasColumn('tenants', 'data')) {
                Schema::table('tenants', function (Blueprint $table) {
                    $table->json('data')->nullable();
                });
            }
            
            // Add subscription column if it doesn't exist
            if (!Schema::hasColumn('tenants', 'subscription')) {
                Schema::table('tenants', function (Blueprint $table) {
                    $table->json('subscription')->nullable();
                });
            }
        }

        // Set default status for existing tenants
        $tenants = DB::table('tenants')->get();
        foreach ($tenants as $tenant) {
            $data = json_decode($tenant->data ?? '{}', true) ?: [];
            
            // Only update if status is not set
            if (!isset($data['status'])) {
                $data['status'] = 'active';
                
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['data' => json_encode($data)]);
            }
            
            // Set default subscription if not set
            if (empty($tenant->subscription)) {
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['subscription' => json_encode(['plan' => 'free'])]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop these columns as they may contain important data
    }
};

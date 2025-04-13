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
        // First ensure data column exists (for status)
        if (!Schema::hasColumn('tenants', 'data')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->json('data')->nullable();
            });
        }

        // Then add subscription column
        if (!Schema::hasColumn('tenants', 'subscription')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->json('subscription')->nullable();
            });
        }
        
        // Update existing tenants with default values
        DB::table('tenants')
            ->whereNull('data')
            ->orWhere('data', '{}')
            ->update(['data' => json_encode(['status' => 'active'])]);
            
        DB::table('tenants')
            ->whereNull('subscription')
            ->update(['subscription' => json_encode(['plan' => 'free'])]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't drop the data column as it may contain other important info
        
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('subscription');
        });
    }
};

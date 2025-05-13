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
        if (!Schema::hasColumn('tenants', 'data')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->json('data')->nullable();
            });

            // Set default status for existing tenants
            DB::statement('UPDATE tenants SET data = JSON_OBJECT("status", "active") WHERE data IS NULL');
        } else {
            // If column exists, make sure all tenants have a status
            $tenants = DB::table('tenants')->whereNull('data')->orWhere('data', '{}')->get();
            foreach ($tenants as $tenant) {
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['data' => json_encode(['status' => 'active'])]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to remove the data column if it's used by the system
    }
};

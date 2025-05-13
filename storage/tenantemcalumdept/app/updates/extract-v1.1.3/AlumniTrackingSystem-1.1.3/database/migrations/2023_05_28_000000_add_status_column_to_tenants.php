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
        // Add status column if it doesn't exist
        if (!Schema::hasColumn('tenants', 'status')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('status', 50)->default('active')->after('id');
            });
            
            // Migrate existing status values from the data JSON
            $tenants = DB::table('tenants')->get();
            foreach ($tenants as $tenant) {
                $data = json_decode($tenant->data, true) ?: [];
                $status = $data['status'] ?? 'active';
                
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['status' => $status]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Before removing the column, save status values back to data JSON
        if (Schema::hasColumn('tenants', 'status')) {
            $tenants = DB::table('tenants')->get();
            foreach ($tenants as $tenant) {
                $data = json_decode($tenant->data, true) ?: [];
                $data['status'] = $tenant->status;
                
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['data' => json_encode($data)]);
            }
            
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};

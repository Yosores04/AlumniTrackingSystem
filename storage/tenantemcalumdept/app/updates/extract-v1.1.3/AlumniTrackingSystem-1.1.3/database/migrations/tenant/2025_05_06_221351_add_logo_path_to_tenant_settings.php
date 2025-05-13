<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('tenant_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('tenant_settings', 'logo_path')) {
                    $table->string('logo_path')->nullable()->after('text_color');
                }
                
                if (!Schema::hasColumn('tenant_settings', 'background_image_path')) {
                    $table->string('background_image_path')->nullable()->after('logo_path');
                }
            });
            
            Log::info('Added logo_path and background_image_path columns to tenant_settings table');
        } catch (\Exception $e) {
            Log::error('Error adding columns to tenant_settings: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('tenant_settings', function (Blueprint $table) {
                $table->dropColumn(['logo_path', 'background_image_path']);
            });
        } catch (\Exception $e) {
            Log::error('Error dropping columns from tenant_settings: ' . $e->getMessage());
        }
    }
};

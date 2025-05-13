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
            Schema::table('alumni', function (Blueprint $table) {
                // Change column type from VARCHAR(255) to TEXT to allow longer URLs
                $table->text('profile_photo_url')->nullable()->change();
            });
            
            Log::info('Changed profile_photo_url column type to TEXT');
        } catch (\Exception $e) {
            Log::error('Error changing profile_photo_url column type: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('alumni', function (Blueprint $table) {
                // Change back to VARCHAR(255)
                $table->string('profile_photo_url', 255)->nullable()->change();
            });
        } catch (\Exception $e) {
            Log::error('Error reverting profile_photo_url column type: ' . $e->getMessage());
        }
    }
};

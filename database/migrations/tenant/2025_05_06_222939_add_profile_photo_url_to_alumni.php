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
                if (!Schema::hasColumn('alumni', 'profile_photo_url')) {
                    $table->string('profile_photo_url')->nullable()->after('profile_photo_path');
                }
            });
            
            Log::info('Added profile_photo_url column to alumni table');
        } catch (\Exception $e) {
            Log::error('Error adding profile_photo_url column to alumni table: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('alumni', function (Blueprint $table) {
                $table->dropColumn('profile_photo_url');
            });
        } catch (\Exception $e) {
            Log::error('Error dropping profile_photo_url column from alumni table: ' . $e->getMessage());
        }
    }
};

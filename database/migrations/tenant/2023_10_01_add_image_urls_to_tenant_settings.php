<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('tenant_settings', function (Blueprint $table) {
        //     $table->string('logo_url')->nullable()->after('logo_path');
        //     $table->string('background_image_url')->nullable()->after('background_image_path');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('tenant_settings', function (Blueprint $table) {
        //     $table->dropColumn('logo_url');
        //     $table->dropColumn('background_image_url');
        // });
    }
}; 
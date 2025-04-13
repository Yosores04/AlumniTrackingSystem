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
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Alumni Tracking System');
            $table->text('site_description')->nullable();
            $table->string('primary_color')->default('#F53003');
            $table->string('secondary_color')->default('#1B1B18');
            $table->string('accent_color')->default('#3E3E3A');
            $table->string('background_color')->default('#FDFDFC');
            $table->string('text_color')->default('#1B1B18');
            $table->string('logo_path')->nullable();
            $table->string('background_image_path')->nullable();
            $table->text('welcome_message')->nullable();
            $table->text('footer_text')->nullable();
            $table->boolean('show_social_links')->default(true);
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
}; 
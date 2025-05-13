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
            
            Schema::create('tenant_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_name')->default('Alumni Tracking System');
                $table->text('site_description')->nullable();
                $table->string('primary_color')->default('#4f46e5');
                $table->string('secondary_color')->default('#1f2937');
                $table->string('accent_color')->default('#06b6d4');
                $table->string('background_color')->default('#ffffff');
                $table->string('text_color')->default('#111827');
                $table->string('logo_url')->nullable();
                $table->string('background_image_url')->nullable();
                $table->text('welcome_message')->nullable();
                $table->text('footer_text')->nullable();
                $table->boolean('show_social_links')->default(false);
                $table->string('facebook_url')->nullable();
                $table->string('twitter_url')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('linkedin_url')->nullable();
                $table->boolean('is_public')->default(true);
                $table->timestamps();
            });
            
            Log::info('tenant_settings table created successfully');
        } catch (\Exception $e) {
            // Log other errors but don't throw them to prevent migration failure
            Log::error('Error creating tenant_settings table: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::dropIfExists('tenant_settings');
        } catch (\Exception $e) {
            Log::error('Error dropping tenant_settings table: ' . $e->getMessage());
        }
    }
}; 
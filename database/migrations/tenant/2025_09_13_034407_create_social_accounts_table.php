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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('platform'); // facebook, linkedin, twitter, instagram, etc.
            $table->string('username')->nullable();
            $table->string('profile_url')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_public')->default(true);
            $table->json('metadata')->nullable(); // For storing platform-specific data
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate platform entries per alumni
            $table->unique(['alumni_id', 'platform']);
            
            // Index for faster queries
            $table->index(['platform', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};

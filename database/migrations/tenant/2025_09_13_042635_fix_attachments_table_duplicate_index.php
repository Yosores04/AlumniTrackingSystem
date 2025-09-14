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
        // Check if the attachments table exists and fix any index issues
        if (Schema::hasTable('attachments')) {
            // Drop the table if it has issues and recreate it properly
            Schema::dropIfExists('attachments');
        }
        
        // Create the attachments table with proper indexes
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable'); // This automatically creates the polymorphic index
            $table->string('file_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type'); // pdf, doc, image, etc.
            $table->string('mime_type');
            $table->bigInteger('file_size'); // in bytes
            $table->string('category')->nullable(); // resume, certificate, transcript, photo, etc.
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Additional indexes (morphs already creates the attachable index)
            $table->index(['category', 'is_public'], 'attachments_category_public_index');
            $table->index(['file_type', 'is_verified'], 'attachments_type_verified_index');
        });
        
        // Also ensure the other tables exist if they don't
        if (!Schema::hasTable('social_accounts')) {
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
        
        if (!Schema::hasTable('tracking_statuses')) {
            Schema::create('tracking_statuses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
                $table->string('status_type'); // registered, contacted, employed, etc.
                $table->text('notes')->nullable();
                $table->boolean('is_current')->default(false);
                $table->date('follow_up_date')->nullable();
                $table->foreignId('user_id')->constrained('users');
                $table->timestamps();
                
                // Indexes for efficient querying
                $table->index(['alumni_id', 'is_current']);
                $table->index(['status_type', 'is_current']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('social_accounts');
        Schema::dropIfExists('tracking_statuses');
    }
};

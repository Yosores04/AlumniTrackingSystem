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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable'); // Polymorphic relationship (alumni_id, alumni_type)
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
            
            // Indexes for better performance (morphs already creates attachable index)
            $table->index(['category', 'is_public']);
            $table->index(['file_type', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};

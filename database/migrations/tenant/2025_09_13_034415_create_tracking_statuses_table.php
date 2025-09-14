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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_statuses');
    }
};

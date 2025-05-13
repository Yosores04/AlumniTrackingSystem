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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->year('batch_year')->nullable();
            $table->date('graduation_date')->nullable();
            $table->string('department')->nullable();
            $table->string('degree')->nullable();
            $table->enum('employment_status', ['employed', 'unemployed', 'self_employed', 'student', 'other'])->nullable();
            $table->string('current_employer')->nullable();
            $table->string('job_title')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
}; 
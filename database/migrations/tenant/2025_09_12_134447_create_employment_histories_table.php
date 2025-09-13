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
        Schema::create('employment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('position');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship', 'freelance'])->default('full_time');
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->text('achievements')->nullable();
            $table->json('skills')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_histories');
    }
};

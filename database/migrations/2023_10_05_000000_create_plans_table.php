<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip this migration in tenant contexts
        if (App::bound('tenant')) {
            return;
        }
        
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 8, 2)->default(0);
            $table->decimal('annual_price', 8, 2)->default(0);
            $table->integer('max_alumni')->default(0); // 0 means unlimited
            $table->integer('max_instructors')->default(1);
            $table->boolean('has_custom_fields')->default(false);
            $table->boolean('has_advanced_analytics')->default(false);
            $table->boolean('has_integrations')->default(false);
            $table->boolean('has_job_board')->default(false);
            $table->boolean('has_custom_branding')->default(false);
            $table->string('support_level')->default('community');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip this migration in tenant contexts
        if (App::bound('tenant')) {
            return;
        }
        
        Schema::dropIfExists('plans');
    }
}; 
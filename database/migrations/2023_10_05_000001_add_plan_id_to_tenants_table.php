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
        
        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('status')->constrained('plans');
            $table->string('billing_cycle')->default('monthly')->after('plan_id'); // monthly or annual
            $table->timestamp('plan_expires_at')->nullable()->after('billing_cycle');
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
        
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_id');
            $table->dropColumn(['billing_cycle', 'plan_expires_at']);
        });
    }
}; 
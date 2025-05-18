<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        // Get all domains that contain double ports
        $domains = DB::table('domains')->get();
        
        foreach ($domains as $domain) {
            // Check if domain contains double port (:8000:8000)
            if (str_contains($domain->domain, ':8000:8000')) {
                // Fix by replacing double port with single port
                $fixedDomain = str_replace(':8000:8000', ':8000', $domain->domain);
                
                // Update the domain
                DB::table('domains')
                    ->where('id', $domain->id)
                    ->update(['domain' => $fixedDomain]);
                    
                echo "Fixed domain: {$domain->domain} -> {$fixedDomain}\n";
            }
        }
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        // Cannot reliably undo this operation
    }
}; 
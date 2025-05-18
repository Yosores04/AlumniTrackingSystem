<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations to fix domains with double port issues.
     */
    public function up(): void
    {
        // Get all domains with port issues
        $domains = DB::table('domains')->get();
        
        foreach ($domains as $domain) {
            // Case 1: Fix domains with double port (example.localhost:8000:8000)
            if (str_contains($domain->domain, ':8000:8000')) {
                $fixedDomain = str_replace(':8000:8000', '', $domain->domain);
                $this->updateDomain($domain->id, $fixedDomain);
                continue;
            }
            
            // Case 2: Fix domains with single port (example.localhost:8000)
            if (str_ends_with($domain->domain, ':8000')) {
                $fixedDomain = substr($domain->domain, 0, -5);
                $this->updateDomain($domain->id, $fixedDomain);
            }
        }
        
        Log::info('Domain port fixes applied successfully');
    }

    /**
     * Helper method to update a domain and log the change
     */
    private function updateDomain($id, $fixedDomain)
    {
        $original = DB::table('domains')->where('id', $id)->value('domain');
        
        DB::table('domains')
            ->where('id', $id)
            ->update(['domain' => $fixedDomain]);
            
        Log::info('Fixed domain', [
            'id' => $id,
            'original' => $original,
            'fixed' => $fixedDomain
        ]);
    }

    /**
     * Reverse the migrations.
     * This is a data correction, so we don't want to reverse it
     */
    public function down(): void
    {
        // No rollback required for this fix
    }
};

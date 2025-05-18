<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDomainPorts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domains:fix-ports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix domain entries that have double ports (:8000:8000)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning domains table for incorrect port entries...');
        
        // Get all domains that contain double ports
        $domains = DB::table('domains')->get();
        $fixed = 0;
        
        foreach ($domains as $domain) {
            // Check if domain contains double port (:8000:8000)
            if (str_contains($domain->domain, ':8000:8000')) {
                // Fix by replacing double port with single port
                $fixedDomain = str_replace(':8000:8000', ':8000', $domain->domain);
                
                // Update the domain
                DB::table('domains')
                    ->where('id', $domain->id)
                    ->update(['domain' => $fixedDomain]);
                
                $this->info("Fixed domain: {$domain->domain} → {$fixedDomain}");
                $fixed++;
            }
        }
        
        if ($fixed > 0) {
            $this->info("Fixed {$fixed} domain(s) with incorrect port entries.");
        } else {
            $this->info("No domains with incorrect port entries were found.");
        }
        
        return Command::SUCCESS;
    }
} 
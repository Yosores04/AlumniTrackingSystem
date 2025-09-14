<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestDatabaseSchema extends Command
{
    protected $signature = 'test:database-schema';
    protected $description = 'Test if the new database tables exist in tenant databases';

    public function handle()
    {
        $tables = ['social_accounts', 'attachments', 'tracking_statuses', 'employment_histories', 'instructor_notes'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("✓ Table '{$table}' exists");
            } else {
                $this->error("✗ Table '{$table}' does not exist");
            }
        }
        
        return 0;
    }
}
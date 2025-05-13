<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessEmailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process queued emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing email queue...');
        
        try {
            // Run the queue worker for a limited time
            // This will process any jobs in the queue, including emails
            $this->call('queue:work', [
                '--once' => true,
                '--queue' => 'default',
                '--tries' => 3,
            ]);
            
            $this->info('Email queue processed successfully.');
            Log::info('Email queue processed via command');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to process email queue: ' . $e->getMessage());
            Log::error('Failed to process email queue: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 
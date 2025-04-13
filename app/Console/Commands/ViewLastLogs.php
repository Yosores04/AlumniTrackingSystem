<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewLastLogs extends Command
{
    protected $signature = 'log:view {lines=50}';
    protected $description = 'View the last n lines of the Laravel log file';

    public function handle()
    {
        $lines = $this->argument('lines');
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            $this->error('Log file does not exist.');
            return 1;
        }

        $this->info("Showing last {$lines} lines of log:");
        $this->newLine();
        
        // Read the last n lines of the file
        $file = new \SplFileObject($logPath, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();
        
        $offset = max(0, $lastLine - $lines);
        $last_lines = new \LimitIterator($file, $offset, $lastLine);
        
        foreach ($last_lines as $line) {
            if (str_contains($line, 'Login') || str_contains($line, 'login')) {
                $this->line("<fg=yellow>{$line}</>");
            } else {
                $this->line($line);
            }
        }
        
        return 0;
    }
}

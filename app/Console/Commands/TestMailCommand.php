<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : The email to send the test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify mail configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('email');
        
        $this->info("Attempting to send test email to: $recipient");
        
        try {
            // Display mail configuration
            $this->info("Mail Configuration:");
            $this->info("MAIL_MAILER: " . config('mail.default'));
            $this->info("MAIL_HOST: " . config('mail.mailers.smtp.host'));
            $this->info("MAIL_PORT: " . config('mail.mailers.smtp.port'));
            $this->info("MAIL_USERNAME: " . config('mail.mailers.smtp.username'));
            $this->info("MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption'));
            $this->info("MAIL_FROM_ADDRESS: " . config('mail.from.address'));
            $this->info("MAIL_FROM_NAME: " . config('mail.from.name'));
            
            // Send test email
            Mail::raw('This is a test email from the Alumni Tracking System to verify email functionality.', function (Message $message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Test Email from Alumni Tracking System');
                
                $this->info("From: " . $message->getFrom());
            });
            
            $this->info("Test email sent successfully!");
            Log::info("Test email sent successfully to $recipient");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to send test email: " . $e->getMessage());
            Log::error("Failed to send test email", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
} 
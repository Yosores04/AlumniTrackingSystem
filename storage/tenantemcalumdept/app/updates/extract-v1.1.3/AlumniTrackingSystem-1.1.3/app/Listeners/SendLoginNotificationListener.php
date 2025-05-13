<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\LoginNotification;
use Illuminate\Support\Facades\Log;

class SendLoginNotificationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        Log::info('SendLoginNotificationListener initialized');
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        try {
            Log::info('🔔 Login event detected for user: ' . $event->user->email);
            
            $ipAddress = request()->ip();
            $userAgent = request()->userAgent();
            
            Log::info("📱 Login details - IP: {$ipAddress}, Browser: {$userAgent}");
            
            // Always send notification regardless of email verification status
            Log::info('📧 Preparing to send login notification email to: ' . $event->user->email);
            $event->user->notify(new LoginNotification($ipAddress, $userAgent));
            Log::info('✅ Login notification queued for: ' . $event->user->email);
            
            // Record notification in database for tracking
            \DB::table('email_logs')->insert([
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'type' => 'login_notification',
                'status' => 'queued',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Failed to send login notification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}

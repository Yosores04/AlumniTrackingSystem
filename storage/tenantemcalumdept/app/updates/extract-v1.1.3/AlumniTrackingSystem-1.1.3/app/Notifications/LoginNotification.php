<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoginNotification extends Notification
{
    use Queueable;
    
    protected $ipAddress;
    protected $userAgent;

    /**
     * Create a new notification instance.
     *
     * @param string $ipAddress
     * @param string $userAgent
     */
    public function __construct($ipAddress = null, $userAgent = null)
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(config('app.name') . ' - Login Notification')
                    ->greeting("Hello {$notifiable->name},")
                    ->line('You have successfully logged in to your account.')
                    ->line('Login details:')
                    ->line('Time: ' . now()->format('Y-m-d H:i:s'))
                    ->when($this->ipAddress, function ($message) {
                        return $message->line('IP Address: ' . $this->ipAddress);
                    })
                    ->when($this->userAgent, function ($message) {
                        return $message->line('Browser: ' . $this->userAgent);
                    })
                    ->action('View Dashboard', url('/dashboard'))
                    ->line('If you did not initiate this login, please contact support immediately.')
                    ->salutation('Regards, ' . config('app.name') . ' Team');
    }
}

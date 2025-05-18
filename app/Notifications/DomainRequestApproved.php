<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainRequestApproved extends Notification
{
    use Queueable;

    protected $credentials;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Your Domain Request Has Been Approved!')
            ->greeting('Hello ' . $this->credentials['name'] . '!')
            ->line('We are pleased to inform you that your domain request has been approved.')
            ->line('Your domain is now active: ' . $this->credentials['domain'] . ':8000')
            ->action('Visit Your Domain', $this->credentials['login_url']);
            
        // Add login credentials only if they exist
        if (isset($this->credentials['password'])) {
            $mail->line('You can access your domain using the following credentials:')
                ->line('Email: ' . $this->credentials['email'])
                ->line('Password: ' . $this->credentials['password']);
                
            if (isset($this->credentials['password_expires_at'])) {
                $mail->line('Please note that your password will expire soon. Please log in and change your password as soon as possible.');
            }
        } else {
            $mail->line('Your domain has been created without an admin account. You can visit it directly using the link above.');
        }
        
        return $mail->line('Thank you for choosing our service!');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainRequestApproved extends Notification
{
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
            ->subject('🎉 Your Domain Request Has Been Approved!')
            ->greeting('Hello ' . $this->credentials['name'] . '!')
            ->line('We are pleased to inform you that your domain request has been approved and is now ready for use!')
            ->line('**Your Domain:** ' . $this->credentials['domain'] . ':8000')
            ->action('Visit Your Domain Now', $this->credentials['login_url']);
            
        // Add login credentials prominently
        if (isset($this->credentials['password'])) {
            $mail->line('**IMPORTANT: Your Login Credentials**')
                ->line('Please save these credentials in a secure location:')
                ->line('')  // Empty line for spacing
                ->line('📧 **Email:** ' . $this->credentials['email'])
                ->line('🔑 **Password:** `' . $this->credentials['password'] . '`')
                ->line('')  // Empty line for spacing
                ->line('⚠️ **Security Notice:**')
                ->line('• Please change your password immediately after logging in')
                ->line('• This temporary password will not be shown again')
                ->line('• Keep your credentials secure and do not share them');
        } else {
            $mail->line('Your domain has been created. You can visit it directly using the link above.');
        }
        
        return $mail->line('')
            ->line('Need help getting started? Contact our support team.')
            ->line('Thank you for choosing our Alumni Tracking System! 🚀');
    }
}

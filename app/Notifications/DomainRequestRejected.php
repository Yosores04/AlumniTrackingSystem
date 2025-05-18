<?php

namespace App\Notifications;

use App\Models\DomainRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainRequestRejected extends Notification
{
    use Queueable;

    protected $domainRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(DomainRequest $domainRequest)
    {
        $this->domainRequest = $domainRequest;
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
        return (new MailMessage)
            ->subject('Domain Request Rejected')
            ->greeting('Hello ' . $this->domainRequest->admin_name . '!')
            ->line('We regret to inform you that your domain request for: ' . $this->domainRequest->domain_prefix . '.localhost:8000 has been rejected.')
            ->line('Reason: ' . $this->domainRequest->rejection_reason)
            ->line('If you believe this was in error or would like to submit a new request with different details, please feel free to do so.')
            ->action('Submit New Request', url('/request-domain'))
            ->line('Thank you for your understanding.');
    }
}

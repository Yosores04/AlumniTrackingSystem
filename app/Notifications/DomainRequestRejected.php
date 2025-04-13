<?php

namespace App\Notifications;

use App\Models\DomainRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainRequestRejected extends Notification implements ShouldQueue
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
            ->subject('Domain Request Status Update')
            ->greeting('Hello ' . $this->domainRequest->admin_name . '!')
            ->line('We regret to inform you that your request for the domain ' . $this->domainRequest->domain_prefix . '.localhost has been declined.')
            ->line('Reason for rejection:')
            ->line($this->domainRequest->rejection_reason)
            ->line('You are welcome to submit a new request with a different domain name if needed.')
            ->line('If you have any questions, please contact our support team.')
            ->line('Thank you for your understanding.');
    }
}

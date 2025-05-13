<?php

namespace App\Notifications;

use App\Models\DomainRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainRequestReceived extends Notification implements ShouldQueue
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
            ->subject('Domain Request Received')
            ->greeting('Hello ' . $this->domainRequest->admin_name . '!')
            ->line('We have received your request for the domain: ' . $this->domainRequest->domain_prefix . '.localhost')
            ->line('Your request is now being reviewed by our administrators. You will be notified once it has been processed.')
            ->line('Thank you for your interest in our service!');
    }
}

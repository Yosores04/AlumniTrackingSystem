<?php

namespace App\Notifications;

use App\Models\DomainRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainRequestReceived extends Notification
{
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
            ->subject('✅ Domain Request Received - ' . $this->domainRequest->domain_prefix)
            ->greeting('Hello ' . $this->domainRequest->admin_name . '!')
            ->line('Thank you for submitting your domain request!')
            ->line('')
            ->line('**Requested Domain:** ' . $this->domainRequest->domain_prefix . '.localhost:8000')
            ->line('**Request ID:** #' . $this->domainRequest->id)
            ->line('')
            ->line('📋 **What happens next:**')
            ->line('• Your request is now being reviewed by our administrators')
            ->line('• You will receive an email notification once it has been processed')
            ->line('• If approved, you will receive your login credentials via email')
            ->line('')
            ->line('⏱️ **Review Time:** Usually processed within 1-2 business days')
            ->line('')
            ->line('Thank you for your interest in our Alumni Tracking System!');
    }
}

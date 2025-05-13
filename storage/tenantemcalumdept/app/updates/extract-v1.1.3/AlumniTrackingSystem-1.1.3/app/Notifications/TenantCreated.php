<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantCreated extends Notification
{
    use Queueable;

    protected $tenantInfo;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $tenantInfo)
    {
        $this->tenantInfo = $tenantInfo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $url = 'http://' . $this->tenantInfo['domain'] . ':8000';
        
        return (new MailMessage)
            ->subject('Your New Tenant Has Been Created')
            ->greeting('Hello ' . $this->tenantInfo['name'] . '!')
            ->line('Your tenant account has been successfully created.')
            ->line('Please find your tenant details below:')
            ->line('Tenant ID: ' . $this->tenantInfo['id'])
            ->line('Domain: ' . $this->tenantInfo['domain'])
            ->line('Admin Email: ' . $this->tenantInfo['email'])
            ->line('Admin Password: ' . $this->tenantInfo['password'])
            ->action('Access Your Tenant', $url)
            ->line('Please save this email as your password will not be shown again.')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

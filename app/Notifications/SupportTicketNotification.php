<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use App\Models\TicketResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketNotification extends Notification
{
    use Queueable;

    /**
     * The support ticket instance.
     *
     * @var \App\Models\SupportTicket
     */
    protected $ticket;

    /**
     * The ticket response instance (if this is a response notification).
     *
     * @var \App\Models\TicketResponse|null
     */
    protected $response;

    /**
     * The notification type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @param SupportTicket $ticket
     * @param string $type  'new_ticket', 'new_response'
     * @param TicketResponse|null $response
     */
    public function __construct(SupportTicket $ticket, string $type, ?TicketResponse $response = null)
    {
        $this->ticket = $ticket;
        $this->type = $type;
        $this->response = $response;
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
        $url = route('support.show', $this->ticket->id);
        
        if ($this->type === 'new_ticket') {
            return (new MailMessage)
                ->subject('New Support Ticket: ' . $this->ticket->subject)
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('A new support ticket has been created.')
                ->line('Ticket #' . $this->ticket->id . ': ' . $this->ticket->subject)
                ->line('Priority: ' . ucfirst($this->ticket->priority))
                ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->ticket->status)))
                ->action('View Ticket', $url)
                ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
                ->subject('New Response to Ticket #' . $this->ticket->id . ': ' . $this->ticket->subject)
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('There is a new response to your support ticket.')
                ->line('Ticket #' . $this->ticket->id . ': ' . $this->ticket->subject)
                ->when($this->response, function ($mail) {
                    return $mail->line('Response from: ' . $this->response->user->name)
                                ->line('Message: ' . substr($this->response->message, 0, 100) . (strlen($this->response->message) > 100 ? '...' : ''));
                })
                ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->ticket->status)))
                ->action('View Ticket', $url)
                ->line('Thank you for using our application!');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $data = [
            'ticket_id' => $this->ticket->id,
            'subject' => $this->ticket->subject,
            'type' => $this->type,
            'priority' => $this->ticket->priority,
            'status' => $this->ticket->status,
        ];

        if ($this->response) {
            $data['response_id'] = $this->response->id;
            $data['response_from'] = $this->response->user->name;
            $data['response_message'] = substr($this->response->message, 0, 100);
        }

        return $data;
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\SupportTicket;
use App\Models\TicketResponse;
use App\Notifications\SupportTicketNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Get all admin users for notifications.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdminUsers()
    {
        return User::where(function ($query) {
            $query->where('role', User::ROLE_CENTRAL_ADMIN)
                  ->orWhere('role', User::ROLE_TENANT_ADMIN);
        })->get();
    }
    
    /**
     * Get all instructor users for notifications.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInstructorUsers()
    {
        return User::where('role', User::ROLE_INSTRUCTOR)->get();
    }
    
    /**
     * Send new ticket notification to only tenant admins.
     *
     * @param SupportTicket $ticket
     * @return void
     */
    public function sendNewTicketNotification(SupportTicket $ticket)
    {
        // Send to the ticket creator
        $ticket->user->notify(new SupportTicketNotification($ticket, 'new_ticket'));
        
        // Send only to tenant admins (except the creator if they are an admin)
        $admins = $this->getAdminUsers()
            ->where('id', '!=', $ticket->user_id);
        
        Notification::send($admins, new SupportTicketNotification($ticket, 'new_ticket'));
    }
    
    /**
     * Send new response notification.
     *
     * @param SupportTicket $ticket
     * @param TicketResponse $response
     * @return void
     */
    public function sendNewResponseNotification(SupportTicket $ticket, TicketResponse $response)
    {
        // If the response is from staff, notify the ticket creator
        if ($response->is_staff_reply && $ticket->user_id != $response->user_id) {
            $ticket->user->notify(new SupportTicketNotification($ticket, 'new_response', $response));
        }
        
        // If the response is from the ticket creator, notify only tenant admins
        if (!$response->is_staff_reply) {
            $admins = $this->getAdminUsers()
                ->where('id', '!=', $response->user_id);
            
            Notification::send($admins, new SupportTicketNotification($ticket, 'new_response', $response));
        }
    }
} 
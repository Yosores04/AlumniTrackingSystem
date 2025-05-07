<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'status',
        'priority',
        'attachment_path',
    ];

    /**
     * Get the user that owns the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the responses for the ticket.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(TicketResponse::class);
    }

    /**
     * Get the most recent response for the ticket.
     */
    public function latestResponse(): BelongsTo
    {
        return $this->responses()->latest()->first();
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedCreatedDateAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    /**
     * Get the status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'open' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
        ];

        $color = $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
        $label = ucfirst(str_replace('_', ' ', $this->status));

        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $color . '">' . $label . '</span>';
    }

    /**
     * Get the priority badge HTML
     */
    public function getPriorityBadgeAttribute()
    {
        $colors = [
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-blue-100 text-blue-800',
            'high' => 'bg-yellow-100 text-yellow-800',
            'urgent' => 'bg-red-100 text-red-800',
        ];

        $color = $colors[$this->priority] ?? 'bg-gray-100 text-gray-800';
        $label = ucfirst($this->priority);

        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $color . '">' . $label . '</span>';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alumni_id',
        'instructor_id',
        'note',
        'note_type',
        'priority',
        'is_private',
        'note_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_private' => 'boolean',
        'note_date' => 'date',
    ];

    /**
     * Get the alumni that owns the note.
     */
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Get the instructor that created the note.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Scope a query to only include private notes.
     */
    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope a query to only include public notes.
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope a query to filter by note type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('note_type', $type);
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopeWithPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}

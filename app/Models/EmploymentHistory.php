<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alumni_id',
        'company_name',
        'position',
        'location',
        'description',
        'start_date',
        'end_date',
        'employment_type',
        'salary',
        'currency',
        'achievements',
        'skills',
        'is_current',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'salary' => 'decimal:2',
        'skills' => 'array',
        'is_current' => 'boolean',
    ];

    /**
     * Get the alumni that owns the employment history.
     */
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Get the duration of employment in months.
     */
    public function getDurationAttribute()
    {
        $startDate = $this->start_date;
        $endDate = $this->end_date ?? now();
        
        return $startDate->diffInMonths($endDate);
    }

    /**
     * Scope a query to only include current positions.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}

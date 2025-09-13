<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alumni';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'batch_year',
        'graduation_date',
        'department',
        'degree',
        'employment_status',
        'current_employer',
        'job_title',
        'linkedin_url',
        'profile_photo_path',
        'profile_photo_url',
        'is_verified',
        'notes',
        'skills',
        'achievements',
        'certifications',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'graduation_date' => 'date',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user that owns the alumni profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the employment histories for the alumni.
     */
    public function employmentHistories()
    {
        return $this->hasMany(EmploymentHistory::class)->orderBy('start_date', 'desc');
    }

    /**
     * Get the instructor notes for the alumni.
     */
    public function instructorNotes()
    {
        return $this->hasMany(InstructorNote::class)->with('instructor')->orderBy('created_at', 'desc');
    }

    /**
     * Get only public instructor notes for the alumni.
     */
    public function publicInstructorNotes()
    {
        return $this->hasMany(InstructorNote::class)->where('is_private', false)->with('instructor')->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope a query to only include alumni from a specific batch year.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBatchYear($query, $year)
    {
        return $query->where('batch_year', $year);
    }

    /**
     * Scope a query to only include employed alumni.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmployed($query)
    {
        return $query->where('employment_status', 'employed');
    }

    /**
     * Scope a query to only include unemployed alumni.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnemployed($query)
    {
        return $query->where('employment_status', 'unemployed');
    }

    /**
     * Get the current employment history.
     */
    public function currentEmployment()
    {
        return $this->hasOne(EmploymentHistory::class)->where('is_current', true);
    }
} 
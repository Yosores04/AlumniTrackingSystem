<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * Force the model to always use the central database connection
     */
    protected $connection = 'mysql'; // or whatever your central connection name is

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'annual_price',
        'max_alumni',
        'max_instructors',
        'has_custom_fields',
        'has_advanced_analytics',
        'has_integrations',
        'has_job_board',
        'has_custom_branding',
        'support_level',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monthly_price' => 'decimal:2',
        'annual_price' => 'decimal:2',
        'max_alumni' => 'integer',
        'max_instructors' => 'integer',
        'has_custom_fields' => 'boolean',
        'has_advanced_analytics' => 'boolean',
        'has_integrations' => 'boolean',
        'has_job_board' => 'boolean',
        'has_custom_branding' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tenants associated with this plan.
     */
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * Check if the plan has unlimited alumni.
     */
    public function hasUnlimitedAlumni()
    {
        return $this->max_alumni === 0;
    }

    /**
     * Check if the plan has unlimited instructors.
     */
    public function hasUnlimitedInstructors()
    {
        return $this->max_instructors === 0;
    }

    /**
     * Get the annual discount percentage.
     */
    public function getAnnualDiscountPercentage()
    {
        if ($this->monthly_price <= 0) {
            return 0;
        }

        $annualCost = $this->annual_price;
        $monthlyCostForYear = $this->monthly_price * 12;
        
        if ($monthlyCostForYear <= 0) {
            return 0;
        }

        return round(100 - (($annualCost / $monthlyCostForYear) * 100));
    }
} 
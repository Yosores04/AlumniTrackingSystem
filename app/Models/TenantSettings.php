<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_name',
        'site_description',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'logo_path',
        'background_image_path',
        'logo_url',
        'background_image_url',
        'welcome_message',
        'footer_text',
        'show_social_links',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'show_social_links' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * Get the tenant default settings.
     *
     * @return self
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'site_name' => 'Alumni Tracking System',
                'primary_color' => '#F53003',
                'secondary_color' => '#1B1B18',
                'accent_color' => '#3E3E3A',
                'background_color' => '#FDFDFC',
                'text_color' => '#1B1B18',
            ]);
        }
        
        return $settings;
    }
} 
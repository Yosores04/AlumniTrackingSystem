<?php

namespace App\Http\Controllers;

use App\Models\TenantSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TenantSettingsController extends Controller
{
    /**
     * Show the settings form.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $settings = TenantSettings::getSettings();
        
        // Get tenant's subscription plan with improved error handling
        $planType = 'free'; // Default to free
        
        try {
            if (function_exists('tenant') && tenant()) {
                // Make sure tenant model's plan relationship is loaded
                $tenantModel = tenant();
                if (method_exists($tenantModel, 'load')) {
                    $tenantModel->load('plan');
                }
                
                // If the tenant has a plan relationship, use that directly (most reliable)
                if ($tenantModel->plan) {
                    $planType = strtolower($tenantModel->plan->slug);
                    Log::info('Plan detected directly from tenant model', [
                        'plan_id' => $tenantModel->plan->id,
                        'plan_slug' => $planType,
                        'plan_name' => $tenantModel->plan->name
                    ]);
                    
                    // Early return with known good plan type
                    return view('tenant.settings.edit', compact('settings', 'planType'));
                }
                
                // Get tenant ID
                $tenantId = tenant('id');
                
                // Get tenant directly from database to ensure fresh data
                $tenantData = DB::table('tenants')->where('id', $tenantId)->first();
                
                // Log tenant information for debugging
                Log::info('Tenant information from database', [
                    'tenant_id' => $tenantId,
                    'raw_data' => json_encode($tenantData),
                    'tenant_plan' => $tenantModel->plan ?? null
                ]);
                
                // First check plan_id (higher priority)
                if ($tenantData && isset($tenantData->plan_id) && $tenantData->plan_id) {
                    // Get plan from plan_id
                    $plan = DB::table('plans')->where('id', $tenantData->plan_id)->first();
                    if ($plan && isset($plan->slug)) {
                        $planType = strtolower($plan->slug);
                        Log::info('Plan detected from plan_id', [
                            'plan_id' => $tenantData->plan_id,
                            'plan_slug' => $planType
                        ]);
                    }
                } 
                // Fallback to subscription field if plan_id doesn't give us a valid plan
                elseif ($tenantData && property_exists($tenantData, 'subscription')) {
                    $subscription = is_string($tenantData->subscription) 
                        ? json_decode($tenantData->subscription, true) 
                        : $tenantData->subscription;
                    
                    Log::info('Subscription data', ['subscription' => $subscription]);
                    
                    if (is_array($subscription) && isset($subscription['plan'])) {
                        $planType = strtolower($subscription['plan']);
                        
                        // Normalize plan type for consistent comparison
                        if (strpos($planType, 'premium') !== false) {
                            $planType = 'premium';
                        } elseif (strpos($planType, 'basic') !== false) {
                            $planType = 'basic';
                        } else {
                            $planType = 'free';
                        }
                        
                        Log::info('Plan detected from subscription field', ['plan' => $planType]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving tenant subscription plan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        // Normalize plan type before returning
        // This ensures consistent behavior in the view regardless of the source
        if (strpos($planType, 'premium') !== false) {
            $planType = 'premium';
        } elseif (strpos($planType, 'basic') !== false) {
            $planType = 'basic';
        } else {
            $planType = 'free';
        }
        
        Log::info('Final plan type for settings page', ['planType' => $planType]);
        
        return view('tenant.settings.edit', compact('settings', 'planType'));
    }

    /**
     * Update the tenant settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Get tenant's subscription plan with improved error handling
        $planType = 'free'; // Default to free
        
        try {
            if (function_exists('tenant') && tenant()) {
                // Make sure tenant model's plan relationship is loaded
                $tenantModel = tenant();
                if (method_exists($tenantModel, 'load')) {
                    $tenantModel->load('plan');
                }
                
                // Log tenant information for debugging
                Log::info('Tenant information for settings update', [
                    'tenant_id' => tenant('id'),
                    'tenant_plan' => $tenantModel->plan ?? null
                ]);
                
                // If the tenant has a plan relationship, use that directly (most reliable)
                if ($tenantModel->plan) {
                    $planType = strtolower($tenantModel->plan->slug);
                    Log::info('Plan detected directly from tenant model for update', [
                        'plan_id' => $tenantModel->plan->id,
                        'plan_slug' => $planType,
                        'plan_name' => $tenantModel->plan->name
                    ]);
                } else {
                // Get tenant ID
                $tenantId = tenant('id');
                
                // Get tenant directly from database to ensure fresh data
                $tenantData = DB::table('tenants')->where('id', $tenantId)->first();
                
                    // First check plan_id (higher priority)
                    if ($tenantData && isset($tenantData->plan_id) && $tenantData->plan_id) {
                        // Get plan from plan_id
                        $plan = DB::table('plans')->where('id', $tenantData->plan_id)->first();
                        if ($plan && isset($plan->slug)) {
                            $planType = strtolower($plan->slug);
                            Log::info('Plan detected for update from plan_id', [
                                'plan_id' => $tenantData->plan_id,
                                'plan_slug' => $planType
                            ]);
                        }
                    }
                    // Fallback to subscription field if plan_id doesn't give us a valid plan
                    elseif ($tenantData && property_exists($tenantData, 'subscription')) {
                    $subscription = is_string($tenantData->subscription) 
                        ? json_decode($tenantData->subscription, true) 
                        : $tenantData->subscription;
                    
                    if (is_array($subscription) && isset($subscription['plan'])) {
                        $planType = strtolower($subscription['plan']);
                        
                        // Normalize plan type for consistent comparison
                        if (strpos($planType, 'premium') !== false) {
                            $planType = 'premium';
                        } elseif (strpos($planType, 'basic') !== false) {
                            $planType = 'basic';
                        } else {
                            $planType = 'free';
                        }
                        
                            Log::info('Plan detected for update from subscription field', ['plan' => $planType]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving tenant subscription plan for update', [
                'error' => $e->getMessage()
            ]);
        }
        
        // Normalize plan type for consistent behavior
        if (strpos($planType, 'premium') !== false) {
            $planType = 'premium';
        } elseif (strpos($planType, 'basic') !== false) {
            $planType = 'basic';
        } else {
            $planType = 'free';
        }
        
        Log::info('Final plan type for settings update', ['planType' => $planType]);
        
        // Base validation rules (available to all plans)
        $validationRules = [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'welcome_message' => 'nullable|string',
            'footer_text' => 'nullable|string',
        ];
        
        // Add color validation for basic and premium plans
        if (in_array($planType, ['basic', 'premium'])) {
            $colorRules = [
                'primary_color' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
                'secondary_color' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
                'accent_color' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
                'background_color' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
                'text_color' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
            ];
            
            $validationRules = array_merge($validationRules, $colorRules);
        }
        
        // Add image and social media validation for premium plan only
        if ($planType === 'premium') {
            $premiumRules = [
                'logo_url' => 'nullable|url',
                'background_image_url' => 'nullable|url',
                'show_social_links' => 'nullable|boolean',
                'facebook_url' => 'nullable|url',
                'twitter_url' => 'nullable|url',
                'instagram_url' => 'nullable|url',
                'linkedin_url' => 'nullable|url',
                'is_public' => 'nullable|boolean',
            ];
            
            $validationRules = array_merge($validationRules, $premiumRules);
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = TenantSettings::getSettings();
        
        // Update basic settings (available to all plans)
        $settings->site_name = $request->site_name;
        $settings->site_description = $request->site_description;
        $settings->welcome_message = $request->welcome_message;
        $settings->footer_text = $request->footer_text;
        
        // Update color settings (for basic and premium plans)
        if (in_array($planType, ['basic', 'premium'])) {
            // Log the incoming request values
            Log::info('Color settings from request', [
                'primary_color' => $request->primary_color,
                'secondary_color' => $request->secondary_color,
                'accent_color' => $request->accent_color,
                'background_color' => $request->background_color,
                'text_color' => $request->text_color
            ]);
            
            // Make sure we always set the color values
            $settings->primary_color = $request->primary_color;
            $settings->secondary_color = $request->secondary_color;
            $settings->accent_color = $request->accent_color;
            $settings->background_color = $request->background_color;
            $settings->text_color = $request->text_color;
            
            // Log the color values after assignment
            Log::info('Setting color values', [
                'primary_color' => $settings->primary_color,
                'secondary_color' => $settings->secondary_color,
                'accent_color' => $settings->accent_color,
                'background_color' => $settings->background_color,
                'text_color' => $settings->text_color
            ]);
        }
        
        // Update premium settings (for premium plan only)
        if ($planType === 'premium') {
            Log::info('Updating premium plan features');
            
            // Handle logo URL
            if ($request->filled('logo_url')) {
                $settings->logo_url = $request->logo_url;
                // Clear file path if it exists
                if ($settings->logo_path) {
                    Storage::disk('public')->delete($settings->logo_path);
                    $settings->logo_path = null;
                }
                Log::info('Logo URL set', ['url' => $request->logo_url]);
            }
            
            // Handle background image URL
            if ($request->filled('background_image_url')) {
                $settings->background_image_url = $request->background_image_url;
                // Clear file path if it exists
                if ($settings->background_image_path) {
                    Storage::disk('public')->delete($settings->background_image_path);
                    $settings->background_image_path = null;
                }
                Log::info('Background image URL set', ['url' => $request->background_image_url]);
            }
            
            // Update social links
            $settings->show_social_links = $request->has('show_social_links');
            $settings->facebook_url = $request->facebook_url;
            $settings->twitter_url = $request->twitter_url;
            $settings->instagram_url = $request->instagram_url;
            $settings->linkedin_url = $request->linkedin_url;
            $settings->is_public = $request->has('is_public');
            
            Log::info('Social links updated', [
                'show_social_links' => $settings->show_social_links,
                'facebook' => $settings->facebook_url ? 'set' : 'not set',
                'twitter' => $settings->twitter_url ? 'set' : 'not set',
                'instagram' => $settings->instagram_url ? 'set' : 'not set',
                'linkedin' => $settings->linkedin_url ? 'set' : 'not set',
                'is_public' => $settings->is_public
            ]);
        }
        
        // Make sure settings are saved
        $settings->save();
        
        // Log final settings object
        Log::info('Final settings after update', [
            'id' => $settings->id,
            'colors' => [
                'primary' => $settings->primary_color,
                'secondary' => $settings->secondary_color
            ]
        ]);

        return redirect()->route('tenant.settings.edit')
            ->with('success', 'Settings updated successfully!');
    }
} 
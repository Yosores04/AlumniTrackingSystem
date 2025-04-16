<?php

namespace App\Http\Controllers;

use App\Models\TenantSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        return view('tenant.settings.edit', compact('settings'));
    }

    /**
     * Update the tenant settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'primary_color_hex' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
            'secondary_color_hex' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
            'accent_color_hex' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
            'background_color_hex' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
            'text_color_hex' => 'required|string|regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_url' => 'nullable|url',
            'background_image_url' => 'nullable|url',
            'welcome_message' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'show_social_links' => 'nullable|boolean',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = TenantSettings::getSettings();
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('tenant-' . tenant('id') . '/logos', 'public');
            $settings->logo_path = $logoPath;
            $settings->logo_url = null; // Clear URL if file is uploaded
        } elseif ($request->filled('logo_url')) {
            // Use logo URL instead
            $settings->logo_url = $request->logo_url;
            // Clear file path if URL is provided
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
                $settings->logo_path = null;
            }
        }
        
        // Handle background image upload
        if ($request->hasFile('background_image')) {
            // Delete old background image if it exists
            if ($settings->background_image_path) {
                Storage::disk('public')->delete($settings->background_image_path);
            }
            
            $backgroundPath = $request->file('background_image')->store('tenant-' . tenant('id') . '/backgrounds', 'public');
            $settings->background_image_path = $backgroundPath;
            $settings->background_image_url = null; // Clear URL if file is uploaded
        } elseif ($request->filled('background_image_url')) {
            // Use background image URL instead
            $settings->background_image_url = $request->background_image_url;
            // Clear file path if URL is provided
            if ($settings->background_image_path) {
                Storage::disk('public')->delete($settings->background_image_path);
                $settings->background_image_path = null;
            }
        }

        // Update other settings
        $settings->site_name = $request->site_name;
        $settings->site_description = $request->site_description;
        $settings->primary_color = $request->primary_color_hex;
        $settings->secondary_color = $request->secondary_color_hex;
        $settings->accent_color = $request->accent_color_hex;
        $settings->background_color = $request->background_color_hex;
        $settings->text_color = $request->text_color_hex;
        $settings->welcome_message = $request->welcome_message;
        $settings->footer_text = $request->footer_text;
        $settings->show_social_links = $request->has('show_social_links');
        $settings->facebook_url = $request->facebook_url;
        $settings->twitter_url = $request->twitter_url;
        $settings->instagram_url = $request->instagram_url;
        $settings->linkedin_url = $request->linkedin_url;
        $settings->is_public = $request->has('is_public');
        
        $settings->save();

        return redirect()->route('tenant.settings.edit')
            ->with('success', 'Settings updated successfully!');
    }
} 
@extends('layouts.app')

@section('title', 'Site Settings')

@section('content')
    <div class="py-8 mt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card shadow-primary">
                <div class="app-card-header bg-secondary-5 border-b border-secondary-20">
                    <h1 class="text-2xl font-semibold">Customize Your Site</h1>
                </div>
                <div class="app-card-body">
                    <div class="container mx-auto">
                        <div class="max-w-4xl mx-auto">
                            @if(session('success'))
                                <div class="bg-primary-10 border border-primary text-primary px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif

                            <!-- Current Plan Information -->
                            <div class="mb-8 p-6 border rounded-lg bg-gradient-to-r from-secondary-5 to-primary-5 shadow-sm">
                                @php
                                    // First try to get plan from tenant model, which should be most accurate
                                    if (function_exists('tenant') && tenant() && tenant()->plan) {
                                        $normalizedPlanType = strtolower(tenant()->plan->slug);
                                        $displayPlan = tenant()->plan->name;
                                    } else {
                                        // Fallback to the planType variable from the controller
                                        $normalizedPlanType = strtolower($planType);
                                        if (strpos($normalizedPlanType, 'premium') !== false) {
                                            $normalizedPlanType = 'premium';
                                            $displayPlan = 'Premium';
                                        } elseif (strpos($normalizedPlanType, 'basic') !== false) {
                                            $normalizedPlanType = 'basic';
                                            $displayPlan = 'Basic';
                                        } else {
                                            $normalizedPlanType = 'free';
                                            $displayPlan = 'Free';
                                        }
                                    }
                                @endphp
                                
                                <h3 class="text-xl font-bold mb-3">Current Plan: {{ $displayPlan }}</h3>
                                <div class="text-sm">
                                    @if($normalizedPlanType === 'free')
                                        <p class="mb-2">You are currently on the <strong>{{ $displayPlan }}</strong>. You can customize:</p>
                                        <ul class="list-disc ml-6 mb-3">
                                            <li>Site Name</li>
                                            <li>Site Description</li>
                                            <li>Welcome Message</li>
                                            <li>Footer Text</li>
                                        </ul>
                                        <p>Upgrade to enable more customization options!</p>
                                    @elseif($normalizedPlanType === 'basic')
                                        <p class="mb-2">You are currently on the <strong>{{ $displayPlan }}</strong>. In addition to the Free Plan features, you can customize:</p>
                                        <ul class="list-disc ml-6 mb-3">
                                            <li>All color settings</li>
                                        </ul>
                                        <p>Upgrade to Premium to unlock all customization options!</p>
                                    @elseif($normalizedPlanType === 'premium')
                                        <p class="mb-2">You are on the <strong>{{ $displayPlan }}</strong> with full customization capabilities:</p>
                                        <ul class="list-disc ml-6 mb-3">
                                            <li>All text settings</li>
                                            <li>All color settings</li>
                                            <li>Logo & Background images</li>
                                            <li>Social media links</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('tenant.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                                @csrf
                                @method('PUT')
                                
                                <!-- General Settings Section -->
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mb-8">
                                    <h2 class="text-xl font-semibold mb-6 text-gray-800 border-b pb-2">General Settings</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                                            <input type="text" id="site_name" name="site_name" value="{{ $settings->site_name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-40 focus:border-primary transition-all">
                                            @error('site_name')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                                            <input type="text" id="site_description" name="site_description" value="{{ $settings->site_description }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-40 focus:border-primary transition-all">
                                            @error('site_description')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="welcome_message" class="block text-sm font-medium text-gray-700 mb-1">Welcome Message</label>
                                        <textarea id="welcome_message" name="welcome_message" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-40 focus:border-primary transition-all">{{ $settings->welcome_message }}</textarea>
                                        @error('welcome_message')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-sm text-gray-500 mt-1">This message will be displayed on your landing page.</p>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-1">Footer Text</label>
                                        <textarea id="footer_text" name="footer_text" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-40 focus:border-primary transition-all">{{ $settings->footer_text }}</textarea>
                                        @error('footer_text')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Brand Colors - Only for Basic and Premium -->
                                @if(in_array($normalizedPlanType, ['basic', 'premium']))
                                <div class="mb-8">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Brand Colors</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Select colors that represent your brand identity. These colors will be used throughout the site for buttons, accents, and UI elements.
                                        <span class="block mt-2 text-xs italic">Note: RGB variables are automatically generated from these colors to enable consistent transparency effects across the application.</span>
                                    </p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Primary Color -->
                                        <div>
                                            <x-input-label for="primary_color" :value="__('Primary Color')" />
                                            <div class="flex mt-1">
                                                <input type="color" id="primary_color" name="primary_color" value="{{ old('primary_color', $settings->primary_color) }}" class="h-10 p-0 border-0 rounded-l-md w-12">
                                                <x-text-input id="primary_color_hex" name="primary_color_hex" type="text" class="rounded-l-none" :value="old('primary_color', $settings->primary_color)" required />
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Main brand color used for buttons, links, and highlights.</p>
                                            <x-input-error :messages="$errors->get('primary_color')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Secondary Color -->
                                        <div>
                                            <x-input-label for="secondary_color" :value="__('Secondary Color')" />
                                            <div class="flex mt-1">
                                                <input type="color" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $settings->secondary_color) }}" class="h-10 p-0 border-0 rounded-l-md w-12">
                                                <x-text-input id="secondary_color_hex" name="secondary_color_hex" type="text" class="rounded-l-none" :value="old('secondary_color', $settings->secondary_color)" required />
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Used for headers, footers, and secondary elements.</p>
                                            <x-input-error :messages="$errors->get('secondary_color')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Accent Colors -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Accent Colors</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Accent colors add visual interest and highlight important elements across your site.
                                        <span class="block mt-2 text-xs italic">Each accent color is also available as an RGB variable (e.g., --accent-color-rgb) for advanced styling with transparency.</span>
                                    </p>
                                    
                                    <div>
                                        <x-input-label for="accent_color" :value="__('Accent Color')" />
                                        <div class="flex mt-1">
                                            <input type="color" id="accent_color" name="accent_color" value="{{ old('accent_color', $settings->accent_color) }}" class="h-10 p-0 border-0 rounded-l-md w-12">
                                            <x-text-input id="accent_color_hex" name="accent_color_hex" type="text" class="rounded-l-none" :value="old('accent_color', $settings->accent_color)" required />
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Used for visual emphasis, tags, badges, and notifications.</p>
                                        <x-input-error :messages="$errors->get('accent_color')" class="mt-2" />
                                    </div>
                                </div>
                                
                                <!-- Content Colors -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Content Colors</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        These colors define the background and text of your site's content areas.
                                        <span class="block mt-2 text-xs italic">Background and text colors are also available as RGB variables for creating subtle gradients and shadows.</span>
                                    </p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Background Color -->
                                        <div>
                                            <x-input-label for="background_color" :value="__('Background Color')" />
                                            <div class="flex mt-1">
                                                <input type="color" id="background_color" name="background_color" value="{{ old('background_color', $settings->background_color) }}" class="h-10 p-0 border-0 rounded-l-md w-12">
                                                <x-text-input id="background_color_hex" name="background_color_hex" type="text" class="rounded-l-none" :value="old('background_color', $settings->background_color)" required />
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Main background color of content areas.</p>
                                            <x-input-error :messages="$errors->get('background_color')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Text Color -->
                                        <div>
                                            <x-input-label for="text_color" :value="__('Text Color')" />
                                            <div class="flex mt-1">
                                                <input type="color" id="text_color" name="text_color" value="{{ old('text_color', $settings->text_color) }}" class="h-10 p-0 border-0 rounded-l-md w-12">
                                                <x-text-input id="text_color_hex" name="text_color_hex" type="text" class="rounded-l-none" :value="old('text_color', $settings->text_color)" required />
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Main text color for content.</p>
                                            <x-input-error :messages="$errors->get('text_color')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                @else
                                <!-- Message for users on free plan -->
                                <div class="mb-8 p-4 border border-dashed border-gray-300 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-600 mb-2">Color Customization</h3>
                                    <p class="text-gray-500">
                                        <i class="fas fa-lock mr-2"></i>
                                        Color customization is available on the Basic and Premium plans. 
                                        <a href="{{ route('tenant.plan.upgrade.request', 'basic') }}" 
                                           class="text-primary underline">
                                            Upgrade your plan
                                        </a> 
                                        to unlock this feature.
                                    </p>
                                </div>
                                @endif
                                
                                <!-- Logo & Background Section - Only for Premium -->
                                @if($normalizedPlanType === 'premium')
                                <div class="mt-8">
                                    <h2 class="section-heading mb-6">Logo & Background</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <label class="form-label">Logo</label>
                                            <div class="mt-2 app-card shadow-secondary overflow-hidden">
                                                <div class="p-4 flex items-center justify-center bg-secondary-10">
                                                    @if($settings->logo_url)
                                                        <img src="{{ $settings->logo_url }}" alt="Logo" class="max-h-24">
                                                    @else
                                                        <div class="text-muted">No logo set</div>
                                                    @endif
                                                </div>
                                                <div class="p-4 bg-secondary-5">
                                                    <div>
                                                        <label class="block text-sm font-medium">Logo URL</label>
                                                        <input type="text" name="logo_url" value="{{ $settings->logo_url }}" class="form-input mt-1 w-full" placeholder="https://example.com/logo.png">
                                                        <p class="mt-1 text-xs text-gray-500">Enter the URL of your logo image.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="form-label">Background Image</label>
                                            <div class="mt-2 app-card shadow-secondary overflow-hidden">
                                                <div class="p-4 h-32 flex items-center justify-center bg-secondary-10 bg-center bg-cover" style="background-image: url('{{ $settings->background_image_url ?? '' }}')">
                                                    @if(!$settings->background_image_url)
                                                        <div class="text-muted">No background image set</div>
                                                    @endif
                                                </div>
                                                <div class="p-4 bg-secondary-5">
                                                    <div>
                                                        <label class="block text-sm font-medium">Background Image URL</label>
                                                        <input type="text" name="background_image_url" value="{{ $settings->background_image_url }}" class="form-input mt-1 w-full" placeholder="https://example.com/background.jpg">
                                                        <p class="mt-1 text-xs text-gray-500">Enter the URL of your background image.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Social Media Section -->
                                <div class="mt-8">
                                    <h2 class="section-heading mb-6">Social Media</h2>
                                    
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="show_social_links" value="1" {{ $settings->show_social_links ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
                                            <span class="ml-2">Show social media links on site</span>
                                        </label>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="facebook_url" class="form-label">Facebook</label>
                                            <div class="flex">
                                                <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-secondary-10 text-secondary rounded-l-md">
                                                    <i class="fab fa-facebook-f"></i>
                                                </span>
                                                <input type="url" id="facebook_url" name="facebook_url" value="{{ $settings->facebook_url }}" class="form-input rounded-l-none flex-1">
                                            </div>
                                            @error('facebook_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="twitter_url" class="form-label">Twitter</label>
                                            <div class="flex">
                                                <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-secondary-10 text-secondary rounded-l-md">
                                                    <i class="fab fa-twitter"></i>
                                                </span>
                                                <input type="url" id="twitter_url" name="twitter_url" value="{{ $settings->twitter_url }}" class="form-input rounded-l-none flex-1">
                                            </div>
                                            @error('twitter_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="instagram_url" class="form-label">Instagram</label>
                                            <div class="flex">
                                                <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-secondary-10 text-secondary rounded-l-md">
                                                    <i class="fab fa-instagram"></i>
                                                </span>
                                                <input type="url" id="instagram_url" name="instagram_url" value="{{ $settings->instagram_url }}" class="form-input rounded-l-none flex-1">
                                            </div>
                                            @error('instagram_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="linkedin_url" class="form-label">LinkedIn</label>
                                            <div class="flex">
                                                <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-secondary-10 text-secondary rounded-l-md">
                                                    <i class="fab fa-linkedin-in"></i>
                                                </span>
                                                <input type="url" id="linkedin_url" name="linkedin_url" value="{{ $settings->linkedin_url }}" class="form-input rounded-l-none flex-1">
                                            </div>
                                            @error('linkedin_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @else
                                <!-- Message for users not on premium plan -->
                                <div class="mt-8 p-4 border border-dashed border-gray-300 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-600 mb-2">Advanced Customization</h3>
                                    <p class="text-gray-500">
                                        <i class="fas fa-lock mr-2"></i>
                                        Logo, background images, and social media customization is available on the Premium plan. 
                                        <a href="{{ route('tenant.plan.upgrade.request', 'premium') }}" 
                                           class="text-primary underline">
                                            Upgrade to Premium
                                        </a> 
                                        to unlock these features.
                                    </p>
                                </div>
                                @endif
                                
                                <div class="pt-8 mt-6 border-t border-gray-200">
                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-secondary hover:bg-secondary-80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-70 transition-colors">
                                            <i class="fas fa-save mr-2"></i> Save Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle color picker synchronization
        document.addEventListener('DOMContentLoaded', function() {
            const colorInputs = [
                ['primary_color', 'primary_color_hex'],
                ['secondary_color', 'secondary_color_hex'],
                ['accent_color', 'accent_color_hex'],
                ['background_color', 'background_color_hex'],
                ['text_color', 'text_color_hex']
            ];
            
            colorInputs.forEach(pair => {
                if (document.getElementById(pair[0]) && document.getElementById(pair[1])) {
                    const colorPicker = document.getElementById(pair[0]);
                    const hexInput = document.getElementById(pair[1]);
                    
                    // Update text input when color picker changes
                    colorPicker.addEventListener('input', () => {
                        hexInput.value = colorPicker.value;
                    });
                    
                    // Update color picker when text input changes
                    hexInput.addEventListener('input', () => {
                        const hexValue = hexInput.value;
                        if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hexValue)) {
                            colorPicker.value = hexValue;
                        }
                    });
                }
            });
        });
    </script>
    @endpush
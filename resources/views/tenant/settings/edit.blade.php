<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tenant Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container mx-auto">
                        <div class="max-w-4xl mx-auto">
                            <h1 class="text-2xl font-semibold mb-6">Customize Your Site</h1>
                            
                            @if(session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif

                            <form action="{{ route('tenant.settings.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-6">
                                    <h2 class="text-xl font-medium mb-4 pb-2 border-b">General Settings</h2>
                                    
                                    <div class="mb-4">
                                        <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Name</label>
                                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings->site_name) }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">
                                        @error('site_name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="site_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Description</label>
                                        <textarea name="site_description" id="site_description" rows="3" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">{{ old('site_description', $settings->site_description) }}</textarea>
                                        @error('site_description')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="welcome_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Welcome Message</label>
                                        <textarea name="welcome_message" id="welcome_message" rows="4" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">{{ old('welcome_message', $settings->welcome_message) }}</textarea>
                                        @error('welcome_message')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="footer_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Footer Text</label>
                                        <textarea name="footer_text" id="footer_text" rows="3" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">{{ old('footer_text', $settings->footer_text) }}</textarea>
                                        @error('footer_text')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_public" value="1" {{ old('is_public', $settings->is_public) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Make site publicly accessible</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <h2 class="text-xl font-medium mb-4 pb-2 border-b">Appearance</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="primary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Color</label>
                                            <div class="flex">
                                                <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $settings->primary_color) }}" class="h-10 w-10 border rounded-md mr-2">
                                                <input type="text" value="{{ old('primary_color', $settings->primary_color) }}" class="flex-1 px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300" oninput="document.getElementById('primary_color').value = this.value">
                                            </div>
                                            @error('primary_color')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secondary Color</label>
                                            <div class="flex">
                                                <input type="color" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', $settings->secondary_color) }}" class="h-10 w-10 border rounded-md mr-2">
                                                <input type="text" value="{{ old('secondary_color', $settings->secondary_color) }}" class="flex-1 px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300" oninput="document.getElementById('secondary_color').value = this.value">
                                            </div>
                                            @error('secondary_color')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="accent_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Accent Color</label>
                                            <div class="flex">
                                                <input type="color" name="accent_color" id="accent_color" value="{{ old('accent_color', $settings->accent_color) }}" class="h-10 w-10 border rounded-md mr-2">
                                                <input type="text" value="{{ old('accent_color', $settings->accent_color) }}" class="flex-1 px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300" oninput="document.getElementById('accent_color').value = this.value">
                                            </div>
                                            @error('accent_color')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="background_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Background Color</label>
                                            <div class="flex">
                                                <input type="color" name="background_color" id="background_color" value="{{ old('background_color', $settings->background_color) }}" class="h-10 w-10 border rounded-md mr-2">
                                                <input type="text" value="{{ old('background_color', $settings->background_color) }}" class="flex-1 px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300" oninput="document.getElementById('background_color').value = this.value">
                                            </div>
                                            @error('background_color')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="text_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Text Color</label>
                                            <div class="flex">
                                                <input type="color" name="text_color" id="text_color" value="{{ old('text_color', $settings->text_color) }}" class="h-10 w-10 border rounded-md mr-2">
                                                <input type="text" value="{{ old('text_color', $settings->text_color) }}" class="flex-1 px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300" oninput="document.getElementById('text_color').value = this.value">
                                            </div>
                                            @error('text_color')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo</label>
                                        @if($settings->logo_path)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($settings->logo_path) }}" alt="Current Logo" class="h-16 border p-1 rounded">
                                            </div>
                                        @endif
                                        <input type="file" name="logo" id="logo" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @error('logo')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="background_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hero Background Image</label>
                                        @if($settings->background_image_path)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($settings->background_image_path) }}" alt="Current Background" class="h-32 border p-1 rounded">
                                            </div>
                                        @endif
                                        <input type="file" name="background_image" id="background_image" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @error('background_image')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <h2 class="text-xl font-medium mb-4 pb-2 border-b">Social Media</h2>
                                    
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="show_social_links" value="1" {{ old('show_social_links', $settings->show_social_links) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show social media links</span>
                                        </label>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Facebook URL</label>
                                            <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">
                                            @error('facebook_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="twitter_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Twitter URL</label>
                                            <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">
                                            @error('twitter_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instagram URL</label>
                                            <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">
                                            @error('instagram_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="linkedin_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LinkedIn URL</label>
                                            <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring focus:border-blue-300">
                                            @error('linkedin_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300">
                                        Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

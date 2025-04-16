<style>
    /* Settings page styles */
    .settings-card {
        background-color: var(--content-bg);
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .tab-button {
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .tab-button:hover {
        background-color: var(--brand-primary-light);
        color: var(--brand-primary);
    }
    
    .tab-button.active {
        background-color: var(--brand-primary);
        color: white;
    }
    
    .tab-button i {
        margin-right: 0.75rem;
        width: 1.25rem;
        text-align: center;
    }
    
    .settings-heading {
        color: var(--text-primary);
        border-bottom: 2px solid var(--brand-primary-light);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-input {
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 0.375rem;
        padding: 0.625rem;
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .form-input:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px var(--brand-primary-light);
        outline: none;
    }
    
    .submit-btn {
        background-color: var(--brand-primary);
        color: white;
        border-radius: 0.375rem;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    
    .submit-btn:hover {
        background-color: var(--primary-hover);
    }
    
    .color-preview {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 0.25rem;
        border: 1px solid rgba(0,0,0,0.1);
        display: inline-block;
        vertical-align: middle;
        margin-right: 0.5rem;
    }
</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">Settings</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Settings Navigation -->
            <div class="md:col-span-1">
                <nav class="settings-card p-4">
                    <button class="tab-button active w-full text-left" data-tab="general">
                        <i class="fas fa-cog"></i> General
                    </button>
                    <button class="tab-button w-full text-left" data-tab="appearance">
                        <i class="fas fa-paint-brush"></i> Appearance
                    </button>
                    <button class="tab-button w-full text-left" data-tab="notifications">
                        <i class="fas fa-bell"></i> Notifications
                    </button>
                    <button class="tab-button w-full text-left" data-tab="privacy">
                        <i class="fas fa-shield-alt"></i> Privacy
                    </button>
                    <button class="tab-button w-full text-left" data-tab="integrations">
                        <i class="fas fa-plug"></i> Integrations
                    </button>
                </nav>
            </div>
            
            <!-- Settings Content -->
            <div class="md:col-span-3">
                <!-- General Settings -->
                <div class="settings-card p-6 tab-content" id="general">
                    <h2 class="settings-heading text-lg font-semibold">General Settings</h2>
                    
                    <form action="{{ route('tenant.settings.updateGeneral') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" id="site_name" name="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}" class="form-input">
                            </div>
                            
                            <div>
                                <label for="site_email" class="form-label">Contact Email</label>
                                <input type="email" id="site_email" name="site_email" value="{{ $settings['site_email'] ?? '' }}" class="form-input">
                            </div>
                            
                            <div>
                                <label for="alumni_title" class="form-label">Alumni Title</label>
                                <input type="text" id="alumni_title" name="alumni_title" value="{{ $settings['alumni_title'] ?? 'Alumni' }}" class="form-input">
                            </div>
                            
                            <div>
                                <label for="graduation_term" class="form-label">Graduation Term</label>
                                <select id="graduation_term" name="graduation_term" class="form-input">
                                    <option value="Batch" {{ ($settings['graduation_term'] ?? '') == 'Batch' ? 'selected' : '' }}>Batch</option>
                                    <option value="Class" {{ ($settings['graduation_term'] ?? '') == 'Class' ? 'selected' : '' }}>Class</option>
                                    <option value="Year" {{ ($settings['graduation_term'] ?? '') == 'Year' ? 'selected' : '' }}>Year</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="submit-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
                
                <!-- Appearance Settings -->
                <div class="settings-card p-6 tab-content hidden" id="appearance">
                    <h2 class="settings-heading text-lg font-semibold">Appearance Settings</h2>
                    
                    <form action="{{ route('tenant.settings.updateAppearance') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label class="form-label">Logo</label>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 border border-gray-200 rounded-md flex items-center justify-center">
                                    @if($settings['logo'] ?? null)
                                        <img src="{{ Storage::url($settings['logo']) }}" alt="Logo" class="max-w-full max-h-full">
                                    @else
                                        <i class="fas fa-image text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                                <input type="file" name="logo" id="logo" class="hidden" accept="image/*">
                                <label for="logo" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md cursor-pointer hover:bg-gray-200">
                                    Choose File
                                </label>
                                @if($settings['logo'] ?? null)
                                    <button type="button" class="text-red-500 hover:text-red-700" id="remove_logo">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <h3 class="font-medium mb-3 mt-6">Color Scheme</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Brand Colors -->
                            <div>
                                <h4 class="font-medium text-sm mb-2 text-gray-600">Brand Colors</h4>
                                <div class="space-y-3">
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['brand-primary'] ?? '#4F46E5' }}"></span>
                                            Primary
                                        </label>
                                        <input type="color" name="colors[brand-primary]" value="{{ $settings['colors']['brand-primary'] ?? '#4F46E5' }}" class="w-full h-8">
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['brand-secondary'] ?? '#9333EA' }}"></span>
                                            Secondary
                                        </label>
                                        <input type="color" name="colors[brand-secondary]" value="{{ $settings['colors']['brand-secondary'] ?? '#9333EA' }}" class="w-full h-8">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- UI Colors -->
                            <div>
                                <h4 class="font-medium text-sm mb-2 text-gray-600">UI Colors</h4>
                                <div class="space-y-3">
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['ui-background'] ?? '#F9FAFB' }}"></span>
                                            Background
                                        </label>
                                        <input type="color" name="colors[ui-background]" value="{{ $settings['colors']['ui-background'] ?? '#F9FAFB' }}" class="w-full h-8">
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['ui-card'] ?? '#FFFFFF' }}"></span>
                                            Content Background
                                        </label>
                                        <input type="color" name="colors[ui-card]" value="{{ $settings['colors']['ui-card'] ?? '#FFFFFF' }}" class="w-full h-8">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Text Colors -->
                            <div>
                                <h4 class="font-medium text-sm mb-2 text-gray-600">Text Colors</h4>
                                <div class="space-y-3">
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['text-primary'] ?? '#111827' }}"></span>
                                            Primary Text
                                        </label>
                                        <input type="color" name="colors[text-primary]" value="{{ $settings['colors']['text-primary'] ?? '#111827' }}" class="w-full h-8">
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['text-secondary'] ?? '#6B7280' }}"></span>
                                            Secondary Text
                                        </label>
                                        <input type="color" name="colors[text-secondary]" value="{{ $settings['colors']['text-secondary'] ?? '#6B7280' }}" class="w-full h-8">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Colors -->
                            <div>
                                <h4 class="font-medium text-sm mb-2 text-gray-600">Status Colors</h4>
                                <div class="space-y-3">
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['status-success'] ?? '#10B981' }}"></span>
                                            Success
                                        </label>
                                        <input type="color" name="colors[status-success]" value="{{ $settings['colors']['status-success'] ?? '#10B981' }}" class="w-full h-8">
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <label class="form-label text-sm">
                                            <span class="color-preview" style="background-color: {{ $settings['colors']['status-error'] ?? '#EF4444' }}"></span>
                                            Error
                                        </label>
                                        <input type="color" name="colors[status-error]" value="{{ $settings['colors']['status-error'] ?? '#EF4444' }}" class="w-full h-8">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="submit-btn">Save Appearance</button>
                        </div>
                    </form>
                </div>
                
                <!-- Notification Settings -->
                <div class="settings-card p-6 tab-content hidden" id="notifications">
                    <h2 class="settings-heading text-lg font-semibold">Notification Settings</h2>
                    
                    <form action="{{ route('tenant.settings.updateNotifications') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="notify_new_alumni" name="notify_new_alumni" value="1" 
                                    {{ ($settings['notifications']['notify_new_alumni'] ?? 0) ? 'checked' : '' }} 
                                    class="rounded text-brand-primary focus:ring-primary">
                                <label for="notify_new_alumni" class="ml-2">New alumni registrations</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="notify_events" name="notify_events" value="1" 
                                    {{ ($settings['notifications']['notify_events'] ?? 0) ? 'checked' : '' }} 
                                    class="rounded text-brand-primary focus:ring-primary">
                                <label for="notify_events" class="ml-2">New events</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="notify_jobs" name="notify_jobs" value="1" 
                                    {{ ($settings['notifications']['notify_jobs'] ?? 0) ? 'checked' : '' }} 
                                    class="rounded text-brand-primary focus:ring-primary">
                                <label for="notify_jobs" class="ml-2">New job opportunities</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="notify_news" name="notify_news" value="1" 
                                    {{ ($settings['notifications']['notify_news'] ?? 0) ? 'checked' : '' }} 
                                    class="rounded text-brand-primary focus:ring-primary">
                                <label for="notify_news" class="ml-2">News articles</label>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="font-medium mb-3">Email Notifications</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="email_notifications" name="email_notifications" value="1" 
                                        {{ ($settings['notifications']['email_notifications'] ?? 0) ? 'checked' : '' }} 
                                        class="rounded text-brand-primary focus:ring-primary">
                                    <label for="email_notifications" class="ml-2">Send email notifications</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="email_digest" name="email_digest" value="1" 
                                        {{ ($settings['notifications']['email_digest'] ?? 0) ? 'checked' : '' }} 
                                        class="rounded text-brand-primary focus:ring-primary">
                                    <label for="email_digest" class="ml-2">Send weekly digest</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="submit-btn">Save Notification Settings</button>
                        </div>
                    </form>
                </div>
                
                <!-- Privacy Settings -->
                <div class="settings-card p-6 tab-content hidden" id="privacy">
                    <h2 class="settings-heading text-lg font-semibold">Privacy Settings</h2>
                    
                    <form action="{{ route('tenant.settings.updatePrivacy') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="alumni_visibility" class="form-label">Alumni Directory Visibility</label>
                                <select id="alumni_visibility" name="alumni_visibility" class="form-input">
                                    <option value="public" {{ ($settings['privacy']['alumni_visibility'] ?? '') == 'public' ? 'selected' : '' }}>Public (anyone can view)</option>
                                    <option value="members" {{ ($settings['privacy']['alumni_visibility'] ?? '') == 'members' ? 'selected' : '' }}>Members only (requires login)</option>
                                    <option value="alumni" {{ ($settings['privacy']['alumni_visibility'] ?? '') == 'alumni' ? 'selected' : '' }}>Alumni only (verified alumni)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="profile_fields" class="form-label">Required Profile Fields</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="field_name" name="profile_fields[name]" value="1" 
                                            {{ ($settings['privacy']['profile_fields']['name'] ?? 1) ? 'checked' : '' }} 
                                            class="rounded text-brand-primary focus:ring-primary">
                                        <label for="field_name" class="ml-2">Full Name</label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="field_email" name="profile_fields[email]" value="1" 
                                            {{ ($settings['privacy']['profile_fields']['email'] ?? 1) ? 'checked' : '' }} 
                                            class="rounded text-brand-primary focus:ring-primary">
                                        <label for="field_email" class="ml-2">Email</label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="field_batch" name="profile_fields[batch]" value="1" 
                                            {{ ($settings['privacy']['profile_fields']['batch'] ?? 1) ? 'checked' : '' }} 
                                            class="rounded text-brand-primary focus:ring-primary">
                                        <label for="field_batch" class="ml-2">Batch/Graduation Year</label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="field_course" name="profile_fields[course]" value="1" 
                                            {{ ($settings['privacy']['profile_fields']['course'] ?? 1) ? 'checked' : '' }} 
                                            class="rounded text-brand-primary focus:ring-primary">
                                        <label for="field_course" class="ml-2">Course/Program</label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="field_phone" name="profile_fields[phone]" value="1" 
                                            {{ ($settings['privacy']['profile_fields']['phone'] ?? 0) ? 'checked' : '' }} 
                                            class="rounded text-brand-primary focus:ring-primary">
                                        <label for="field_phone" class="ml-2">Phone Number</label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="field_location" name="profile_fields[location]" value="1" 
                                            {{ ($settings['privacy']['profile_fields']['location'] ?? 0) ? 'checked' : '' }} 
                                            class="rounded text-brand-primary focus:ring-primary">
                                        <label for="field_location" class="ml-2">Location</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="submit-btn">Save Privacy Settings</button>
                        </div>
                    </form>
                </div>
                
                <!-- Integrations -->
                <div class="settings-card p-6 tab-content hidden" id="integrations">
                    <h2 class="settings-heading text-lg font-semibold">Integrations</h2>
                    
                    <form action="{{ route('tenant.settings.updateIntegrations') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <!-- Social Media -->
                            <div>
                                <h3 class="font-medium mb-3">Social Media</h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="facebook_url" class="form-label">Facebook Page</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-md">
                                                <i class="fab fa-facebook"></i>
                                            </span>
                                            <input type="url" id="facebook_url" name="socials[facebook]" value="{{ $settings['integrations']['socials']['facebook'] ?? '' }}" class="form-input rounded-l-none">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="linkedin_url" class="form-label">LinkedIn</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-md">
                                                <i class="fab fa-linkedin"></i>
                                            </span>
                                            <input type="url" id="linkedin_url" name="socials[linkedin]" value="{{ $settings['integrations']['socials']['linkedin'] ?? '' }}" class="form-input rounded-l-none">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="twitter_url" class="form-label">Twitter</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-md">
                                                <i class="fab fa-twitter"></i>
                                            </span>
                                            <input type="url" id="twitter_url" name="socials[twitter]" value="{{ $settings['integrations']['socials']['twitter'] ?? '' }}" class="form-input rounded-l-none">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- API Integrations -->
                            <div>
                                <h3 class="font-medium mb-3">API Keys</h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="google_maps_key" class="form-label">Google Maps API Key</label>
                                        <input type="text" id="google_maps_key" name="api_keys[google_maps]" value="{{ $settings['integrations']['api_keys']['google_maps'] ?? '' }}" class="form-input">
                                    </div>
                                    
                                    <div>
                                        <label for="mailchimp_key" class="form-label">Mailchimp API Key</label>
                                        <input type="text" id="mailchimp_key" name="api_keys[mailchimp]" value="{{ $settings['integrations']['api_keys']['mailchimp'] ?? '' }}" class="form-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="submit-btn">Save Integration Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Hide all tab contents
                tabContents.forEach(tab => {
                    tab.classList.add('hidden');
                });
                
                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Show the selected tab
                document.getElementById(tabId).classList.remove('hidden');
                
                // Add active class to the clicked button
                button.classList.add('active');
            });
        });
        
        // File upload preview
        const fileInput = document.getElementById('logo');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    const imageContainer = this.parentElement.querySelector('div');
                    
                    reader.onload = function(e) {
                        imageContainer.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="max-w-full max-h-full">`;
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        
        // Remove logo
        const removeLogoBtn = document.getElementById('remove_logo');
        if (removeLogoBtn) {
            removeLogoBtn.addEventListener('click', function() {
                const imageContainer = this.parentElement.querySelector('div');
                imageContainer.innerHTML = '<i class="fas fa-image text-gray-300 text-3xl"></i>';
                
                // Add a hidden input to indicate logo removal
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_logo';
                hiddenInput.value = '1';
                this.parentElement.appendChild(hiddenInput);
                
                // Hide the remove button
                this.classList.add('hidden');
            });
        }
    });
</script> 
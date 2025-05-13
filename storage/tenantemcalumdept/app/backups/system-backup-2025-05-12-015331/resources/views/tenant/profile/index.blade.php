<style>
    /* Profile page styles */
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .profile-header {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    
    .profile-header-bg {
        background-color: rgba(var(--brand-primary-rgb), 1);
        height: 150px;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid var(--content-bg);
        background-color: var(--content-bg);
        position: relative;
        margin-top: -75px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .profile-title {
        color: var(--text-secondary);
        font-size: 1rem;
    }
    
    .profile-meta {
        display: flex;
        align-items: center;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }
    
    .profile-meta i {
        margin-right: 0.5rem;
        color: rgba(var(--brand-primary-rgb), 1);
    }
    
    .profile-tabs {
        display: flex;
        border-bottom: 1px solid rgba(var(--content-text-rgb), 0.1);
        margin-bottom: 1rem;
    }
    
    .profile-tab {
        padding: 1rem 1.5rem;
        font-weight: 500;
        color: var(--text-secondary);
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .profile-tab:hover {
        color: rgba(var(--brand-primary-rgb), 1);
    }
    
    .profile-tab.active {
        color: rgba(var(--brand-primary-rgb), 1);
        border-bottom-color: rgba(var(--brand-primary-rgb), 1);
    }
    
    .profile-section {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        box-shadow: 0 2px 5px rgba(var(--content-text-rgb), 0.05);
        margin-bottom: 1.5rem;
    }
    
    .section-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(var(--content-text-rgb), 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-title {
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .section-content {
        padding: 1.5rem;
    }
    
    .info-group {
        margin-bottom: 1.5rem;
    }
    
    .info-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        color: var(--text-primary);
    }
    
    .edit-profile-btn {
        background-color: rgba(var(--brand-primary-rgb), 1);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    
    .edit-profile-btn:hover {
        background-color: var(--primary-hover);
    }
    
    .skill-tag {
        display: inline-block;
        background-color: rgba(var(--brand-primary-rgb), 0.15);
        color: rgba(var(--brand-primary-rgb), 1);
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: rgba(var(--brand-primary-rgb), 0.15);
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: rgba(var(--brand-primary-rgb), 1);
    }
    
    .timeline-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }
    
    .timeline-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    
    .timeline-subtitle {
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }
    
    .timeline-content {
        color: var(--text-primary);
    }

    .profile-card {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .profile-header {
        position: relative;
        height: 150px;
        background-color: rgba(var(--brand-primary-rgb), 0.8);
        background-image: linear-gradient(135deg, 
            rgba(var(--brand-primary-rgb), 0.8) 0%, 
            rgba(var(--brand-secondary-rgb), 0.8) 100%);
    }

    .profile-avatar {
        position: absolute;
        width: 120px;
        height: 120px;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 50%;
        border: 5px solid var(--content-bg);
        background-color: white;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(var(--brand-secondary-rgb), 0.15);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-body {
        padding: 4rem 1.5rem 1.5rem;
        text-align: center;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .profile-headline {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        padding: 1rem 0;
        border-top: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-bottom: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        margin-bottom: 1.5rem;
    }

    .profile-stat {
        text-align: center;
    }

    .profile-stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .profile-stat-label {
        font-size: 0.875rem;
        color: var(--text-tertiary);
    }

    .profile-actions {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .profile-section {
        margin-bottom: 1.5rem;
    }

    .profile-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
    }

    .profile-section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .profile-section-action {
        font-size: 0.875rem;
        color: var(--brand-primary);
    }

    .profile-info-item {
        display: flex;
        margin-bottom: 0.75rem;
    }

    .profile-info-label {
        width: 120px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .profile-info-value {
        flex: 1;
        color: var(--text-primary);
    }

    .profile-experience-item, 
    .profile-education-item {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
    }

    .profile-experience-item:last-child,
    .profile-education-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .profile-experience-header,
    .profile-education-header {
        display: flex;
        margin-bottom: 0.75rem;
    }

    .profile-experience-logo,
    .profile-education-logo {
        width: 3rem;
        height: 3rem;
        border-radius: 0.375rem;
        overflow: hidden;
        margin-right: 1rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-experience-logo img,
    .profile-education-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .profile-experience-details,
    .profile-education-details {
        flex: 1;
    }

    .profile-experience-title,
    .profile-education-degree {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .profile-experience-company,
    .profile-education-school {
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .profile-experience-date,
    .profile-education-date {
        font-size: 0.875rem;
        color: var(--text-tertiary);
    }

    .profile-experience-description,
    .profile-education-description {
        color: var(--text-secondary);
        margin-top: 0.75rem;
        line-height: 1.5;
    }

    .profile-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .profile-skill {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        background-color: rgba(var(--brand-primary-rgb), 0.1);
        color: var(--text-primary);
        border-radius: 9999px;
        font-size: 0.875rem;
    }
</style>

<div class="py-12">
    <div class="profile-container px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="profile-header mb-6">
            <div class="profile-header-bg"></div>
            
            <div class="px-6 pb-6">
                <div class="flex flex-col md:flex-row">
                    <div class="md:mr-6">
                        <div class="profile-avatar mx-auto md:mx-0">
                            <img src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : asset('images/default-avatar.png') }}" alt="{{ $user->name }}">
                        </div>
                    </div>
                    
                    <div class="flex-1 mt-4 md:mt-0 text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h1 class="profile-name">{{ $user->name }}</h1>
                                <p class="profile-title">{{ $user->position ?? 'Alumni' }} {{ $user->graduation_year ? "- Class of " . $user->graduation_year : '' }}</p>
                                
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mt-2">
                                    @if($user->location)
                                    <div class="profile-meta">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $user->location }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($user->email)
                                    <div class="profile-meta">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0">
                                <a href="{{ route('tenant.profile.edit') }}" class="edit-profile-btn">
                                    <i class="fas fa-edit mr-1"></i> Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="profile-tabs">
                <div class="profile-tab active" data-tab="about">About</div>
                <div class="profile-tab" data-tab="education">Education</div>
                <div class="profile-tab" data-tab="experience">Experience</div>
                <div class="profile-tab" data-tab="activities">Activities</div>
            </div>
        </div>
        
        <!-- Profile Content -->
        <div class="profile-content">
            <!-- About Section -->
            <div class="tab-content" id="about-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Personal Information -->
                    <div class="md:col-span-2">
                        <div class="profile-section">
                            <div class="section-header">
                                <h2 class="section-title">Personal Information</h2>
                            </div>
                            <div class="section-content">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="info-group">
                                        <p class="info-label">Full Name</p>
                                        <p class="info-value">{{ $user->name }}</p>
                                    </div>
                                    
                                    <div class="info-group">
                                        <p class="info-label">Email Address</p>
                                        <p class="info-value">{{ $user->email }}</p>
                                    </div>
                                    
                                    <div class="info-group">
                                        <p class="info-label">Phone Number</p>
                                        <p class="info-value">{{ $user->phone ?? 'Not provided' }}</p>
                                    </div>
                                    
                                    <div class="info-group">
                                        <p class="info-label">Location</p>
                                        <p class="info-value">{{ $user->location ?? 'Not provided' }}</p>
                                    </div>
                                    
                                    <div class="info-group">
                                        <p class="info-label">Date of Birth</p>
                                        <p class="info-value">{{ $user->birthday ? $user->birthday->format('F d, Y') : 'Not provided' }}</p>
                                    </div>
                                    
                                    <div class="info-group">
                                        <p class="info-label">Graduation Year</p>
                                        <p class="info-value">{{ $user->graduation_year ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                
                                @if($user->bio)
                                <div class="info-group mt-4">
                                    <p class="info-label">Biography</p>
                                    <p class="info-value">{{ $user->bio }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Skills Section -->
                        <div class="profile-section mt-6">
                            <div class="section-header">
                                <h2 class="section-title">Skills & Expertise</h2>
                            </div>
                            <div class="section-content">
                                @if(count($skills) > 0)
                                <div>
                                    @foreach($skills as $skill)
                                    <span class="skill-tag">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-gray-500">No skills added yet</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact & Social -->
                    <div class="md:col-span-1">
                        <div class="profile-section">
                            <div class="section-header">
                                <h2 class="section-title">Contact & Social</h2>
                            </div>
                            <div class="section-content">
                                <div class="space-y-4">
                                    @if($user->website)
                                    <div class="flex items-center">
                                        <div class="text-brand-primary mr-3">
                                            <i class="fas fa-globe fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="info-label">Website</p>
                                            <a href="{{ $user->website }}" target="_blank" class="text-brand-primary hover:underline">{{ $user->website }}</a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($user->linkedin)
                                    <div class="flex items-center">
                                        <div class="text-brand-primary mr-3">
                                            <i class="fab fa-linkedin fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="info-label">LinkedIn</p>
                                            <a href="{{ $user->linkedin }}" target="_blank" class="text-brand-primary hover:underline">{{ $user->linkedin }}</a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($user->twitter)
                                    <div class="flex items-center">
                                        <div class="text-brand-primary mr-3">
                                            <i class="fab fa-twitter fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="info-label">Twitter</p>
                                            <a href="{{ $user->twitter }}" target="_blank" class="text-brand-primary hover:underline">{{ $user->twitter }}</a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($user->facebook)
                                    <div class="flex items-center">
                                        <div class="text-brand-primary mr-3">
                                            <i class="fab fa-facebook fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="info-label">Facebook</p>
                                            <a href="{{ $user->facebook }}" target="_blank" class="text-brand-primary hover:underline">{{ $user->facebook }}</a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($user->instagram)
                                    <div class="flex items-center">
                                        <div class="text-brand-primary mr-3">
                                            <i class="fab fa-instagram fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="info-label">Instagram</p>
                                            <a href="{{ $user->instagram }}" target="_blank" class="text-brand-primary hover:underline">{{ $user->instagram }}</a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if(!$user->website && !$user->linkedin && !$user->twitter && !$user->facebook && !$user->instagram)
                                    <p class="text-gray-500">No social links added yet</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Education Section -->
            <div class="tab-content hidden" id="education-content">
                <div class="profile-section">
                    <div class="section-header">
                        <h2 class="section-title">Education History</h2>
                    </div>
                    <div class="section-content">
                        @if(count($educations) > 0)
                        <div class="timeline">
                            @foreach($educations as $education)
                            <div class="timeline-item">
                                <p class="timeline-date">{{ $education->start_year }} - {{ $education->end_year ?? 'Present' }}</p>
                                <h3 class="timeline-title">{{ $education->degree }}</h3>
                                <p class="timeline-subtitle">{{ $education->institution }}</p>
                                @if($education->description)
                                <p class="timeline-content">{{ $education->description }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500">No education history added yet</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Experience Section -->
            <div class="tab-content hidden" id="experience-content">
                <div class="profile-section">
                    <div class="section-header">
                        <h2 class="section-title">Work Experience</h2>
                    </div>
                    <div class="section-content">
                        @if(count($experiences) > 0)
                        <div class="timeline">
                            @foreach($experiences as $experience)
                            <div class="timeline-item">
                                <p class="timeline-date">
                                    {{ $experience->start_date->format('M Y') }} - 
                                    {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                                </p>
                                <h3 class="timeline-title">{{ $experience->title }}</h3>
                                <p class="timeline-subtitle">{{ $experience->company }} - {{ $experience->location }}</p>
                                @if($experience->description)
                                <p class="timeline-content">{{ $experience->description }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500">No work experience added yet</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Activities Section -->
            <div class="tab-content hidden" id="activities-content">
                <div class="profile-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Activities</h2>
                    </div>
                    <div class="section-content">
                        @if(count($activities) > 0)
                        <div class="space-y-4">
                            @foreach($activities as $activity)
                            <div class="p-4 bg-gray-50 rounded-md">
                                <div class="flex">
                                    <div class="mr-4">
                                        <div class="h-10 w-10 rounded-full bg-brand-primary-light flex items-center justify-center">
                                            <i class="{{ $activity->icon ?? 'fas fa-star' }} text-brand-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">{{ $activity->created_at->diffForHumans() }}</p>
                                        <p class="font-medium">{{ $activity->description }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500">No recent activities</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.profile-tab');
        const contents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.tab;
                
                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Show target content
                contents.forEach(content => {
                    content.classList.add('hidden');
                    if (content.id === `${target}-content`) {
                        content.classList.remove('hidden');
                    }
                });
            });
        });
    });
</script> 
@extends('layouts.alumni')

@section('title', 'Alumni Portal Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Alerts Section -->
        <div class="mb-6">
            @if(isset($isVerified) && !$isVerified)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Account Pending Verification</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Your alumni profile is pending verification by an administrator. Some features are restricted until your account is verified.</p>
                                <p class="mt-2">You can still <a href="{{ route('support.create') }}" class="font-medium text-yellow-800 hover:text-yellow-900 underline">contact support</a> if you have any questions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Welcome Card -->
        <div class="mb-6 bg-white overflow-hidden shadow rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}!</h2>
            </div>
            <div class="px-6 py-4">
                <p class="text-gray-600">Welcome to your alumni portal. Here you can update your information, connect with other alumni, and stay updated on events and news.</p>
            </div>
        </div>
        
        <!-- Support Section -->
        <div class="mb-8">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-md flex items-center justify-between">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">Need help? Click the button to access our support system.</p>
                    </div>
                </div>
                <a href="{{ url('/support') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm">
                    Support Center
                </a>
            </div>
        </div>
        
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column - Personal Info -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Your Profile</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center mb-4">
                            @if($alumni->profile_photo_path)
                                <img src="{{ Storage::url($alumni->profile_photo_path) }}" alt="{{ $alumni->name }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <span class="text-xl font-bold">{{ substr($alumni->first_name, 0, 1) . substr($alumni->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-md font-medium text-gray-900">{{ $alumni->name }}</div>
                                <div class="text-sm text-gray-500">{{ $alumni->email }}</div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($alumni->is_verified)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Pending Verification
                                </span>
                            @endif
                            
                            @if($alumni->batch_year)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $alumni->batch_year }} Batch
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-4">
                            @if($alumni->graduation_date)
                                <span><i class="fas fa-graduation-cap mr-1"></i> Graduated: {{ $alumni->graduation_date->format('F Y') }}</span><br>
                            @endif
                            @if($alumni->department)
                                <span><i class="fas fa-university mr-1"></i> {{ $alumni->department }}</span><br>
                            @endif
                            @if($alumni->degree)
                                <span><i class="fas fa-award mr-1"></i> {{ $alumni->degree }}</span>
                            @endif
                        </p>
                        
                        <a href="{{ route('alumni.profile') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-user-edit mr-2"></i> Update Profile
                        </a>
                    </div>
                </div>
                
                <!-- Employment Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Employment Information</h3>
                    </div>
                    <div class="px-6 py-4">
                        @if($alumni->employment_status)
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-700 mb-1">Status:</span>
                                @switch($alumni->employment_status)
                                    @case('employed')
                                        <span class="inline-flex items-center bg-green-100 text-green-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-briefcase mr-1"></i> Employed
                                        </span>
                                        @break
                                    @case('unemployed')
                                        <span class="inline-flex items-center bg-red-100 text-red-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-search mr-1"></i> Seeking Opportunities
                                        </span>
                                        @break
                                    @case('self_employed')
                                        <span class="inline-flex items-center bg-indigo-100 text-indigo-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-user-tie mr-1"></i> Self-employed
                                        </span>
                                        @break
                                    @case('student')
                                        <span class="inline-flex items-center bg-purple-100 text-purple-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-graduation-cap mr-1"></i> Student
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center bg-gray-100 text-gray-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-info-circle mr-1"></i> {{ ucfirst($alumni->employment_status) }}
                                        </span>
                                @endswitch
                            </div>
                            
                            @if($alumni->current_employer)
                                <div class="mb-2">
                                    <span class="block text-sm font-medium text-gray-700">Employer:</span>
                                    <span class="text-gray-900">{{ $alumni->current_employer }}</span>
                                </div>
                            @endif
                            
                            @if($alumni->job_title)
                                <div class="mb-2">
                                    <span class="block text-sm font-medium text-gray-700">Job Title:</span>
                                    <span class="text-gray-900">{{ $alumni->job_title }}</span>
                                </div>
                            @endif
                            
                            @if($alumni->linkedin_url)
                                <div class="mt-4">
                                    <a href="{{ $alumni->linkedin_url }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                        <i class="fab fa-linkedin text-xl mr-1"></i> View LinkedIn Profile
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500">No employment information provided yet.</p>
                            <a href="{{ route('alumni.profile') }}" class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-plus-circle mr-1"></i> Add employment details
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Additional Information -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Quick Links Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Links</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-150">
                                <div class="flex-shrink-0 bg-blue-100 rounded-md p-2">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">Alumni Directory</p>
                                    <p class="text-sm text-gray-500">Connect with fellow alumni</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-150">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2">
                                    <i class="fas fa-calendar-alt text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">Upcoming Events</p>
                                    <p class="text-sm text-gray-500">Stay updated with events</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-150">
                                <div class="flex-shrink-0 bg-green-100 rounded-md p-2">
                                    <i class="fas fa-briefcase text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">Job Opportunities</p>
                                    <p class="text-sm text-gray-500">Explore career options</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-150">
                                <div class="flex-shrink-0 bg-purple-100 rounded-md p-2">
                                    <i class="fas fa-newspaper text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">News & Updates</p>
                                    <p class="text-sm text-gray-500">Latest announcements</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Skills & Achievements Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Skills & Achievements</h3>
                        <a href="{{ route('alumni.profile') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Skills -->
                            <div>
                                <h4 class="text-md font-semibold mb-3 flex items-center">
                                    <i class="fas fa-tools text-blue-500 mr-2"></i> Skills
                                </h4>
                                @if($alumni->skills)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(explode(',', $alumni->skills) as $skill)
                                            @if(trim($skill))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                                    {{ trim($skill) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm italic">No skills added yet. <a href="{{ route('alumni.profile') }}" class="text-blue-600 hover:underline">Add your skills</a></p>
                                @endif
                            </div>
                            
                            <!-- Achievements -->
                            <div>
                                <h4 class="text-md font-semibold mb-3 flex items-center">
                                    <i class="fas fa-trophy text-amber-500 mr-2"></i> Achievements
                                </h4>
                                @if($alumni->achievements)
                                    <p class="text-gray-700 text-sm">{{ $alumni->achievements }}</p>
                                @else
                                    <p class="text-gray-500 text-sm italic">No achievements added yet. <a href="{{ route('alumni.profile') }}" class="text-blue-600 hover:underline">Add your achievements</a></p>
                                @endif
                            </div>
                            
                            <!-- Certifications -->
                            <div>
                                <h4 class="text-md font-semibold mb-3 flex items-center">
                                    <i class="fas fa-certificate text-green-500 mr-2"></i> Certifications
                                </h4>
                                @if($alumni->certifications)
                                    <p class="text-gray-700 text-sm">{{ $alumni->certifications }}</p>
                                @else
                                    <p class="text-gray-500 text-sm italic">No certifications added yet. <a href="{{ route('alumni.profile') }}" class="text-blue-600 hover:underline">Add your certifications</a></p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
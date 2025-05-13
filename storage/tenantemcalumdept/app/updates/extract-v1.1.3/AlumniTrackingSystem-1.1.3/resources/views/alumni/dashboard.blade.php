@extends('layouts.alumni')

@section('title', 'Alumni Portal Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-600">Welcome to your alumni portal. Here you can update your information, connect with other alumni, and stay updated on events and news.</p>
                
                @if(!$alumni->is_verified)
                <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                    <p><strong>Note:</strong> Your alumni profile is currently pending verification. Some features may be limited until your account is verified by an administrator.</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Direct Support Access Button -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3 flex-grow">
                    <p class="text-sm text-blue-700">
                        Need help? Click the button below to access our support system.
                    </p>
                </div>
                <div>
                    <a href="{{ url('/support') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Support Center
                    </a>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Your Profile</h3>
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
                    
                    <div class="flex space-x-1 mb-4">
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
                    
                    <a href="{{ route('alumni.profile') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-user-edit mr-2"></i> Update Profile
                    </a>
                </div>
            </div>
            
            <!-- Employment Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Employment Information</h3>
                    
                    @if($alumni->employment_status)
                        <div class="mb-4">
                            <span class="block text-sm font-medium text-gray-700">Status:</span>
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
            
            <!-- Quick Links -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-users mr-2 w-5 text-center"></i>
                                <span>Alumni Directory</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-calendar-alt mr-2 w-5 text-center"></i>
                                <span>Upcoming Events</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-briefcase mr-2 w-5 text-center"></i>
                                <span>Job Opportunities</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-newspaper mr-2 w-5 text-center"></i>
                                <span>News & Updates</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Skills & Achievements -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-3">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Skills & Achievements</h3>
                        <a href="{{ route('alumni.profile') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Skills -->
                        <div>
                            <h4 class="text-md font-semibold mb-2 flex items-center">
                                <i class="fas fa-tools text-blue-500 mr-2"></i> Skills
                            </h4>
                            @if($alumni->skills)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $alumni->skills) as $skill)
                                        @if(trim($skill))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                            <h4 class="text-md font-semibold mb-2 flex items-center">
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
                            <h4 class="text-md font-semibold mb-2 flex items-center">
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

<script>
function debugSupportLink(link) {
    console.log('Support link clicked!');
    console.log('Link href:', link.href);
    
    // Store the original URL for debug display
    const targetUrl = link.href;
    
    // Output debug info to page
    const debugInfo = document.createElement('div');
    debugInfo.className = 'mt-4 p-4 bg-gray-100 rounded';
    debugInfo.innerHTML = `
        <p class="font-bold">Debug Information:</p>
        <p>Target URL: ${targetUrl}</p>
        <p>Current URL: ${window.location.href}</p>
    `;
    link.parentNode.appendChild(debugInfo);
    
    // Direct navigation with timeout to allow seeing the debug info
    setTimeout(() => {
        window.location.href = targetUrl;
    }, 2000);
}
</script>
@endsection 
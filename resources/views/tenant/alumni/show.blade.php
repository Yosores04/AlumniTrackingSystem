@extends(auth()->user()->role === 'instructor' ? 'layouts.instructor' : 'layouts.app')

@section('title', 'Alumni Details')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Alumni Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('alumni.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
            
            @if(auth()->user()->role === 'instructor')
                <a href="{{ route('alumni.instructor-notes.create', ['alumni' => $alumni->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Add Note
                </a>
            @else
                <a href="{{ route('alumni.edit', $alumni->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Profile Overview Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                <div class="px-4 pt-0 pb-6 text-center">
                    <div class="-mt-12 mb-4">
                        @if($alumni->profile_photo_url)
                            <img src="{{ $alumni->profile_photo_url }}" alt="{{ $alumni->name }}" 
                                class="w-24 h-24 rounded-full border-4 border-white mx-auto object-cover shadow-md">
                        @else
                            <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center border-4 border-white mx-auto shadow-md">
                                <span class="text-xl font-bold">{{ substr($alumni->first_name, 0, 1) . substr($alumni->last_name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $alumni->name }}</h3>
                    <p class="text-gray-500 text-sm mb-3">{{ $alumni->email }}</p>
                    
                    <div class="flex justify-center flex-wrap gap-2 mb-3">
                        @if($alumni->batch_year)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $alumni->batch_year }} Batch
                            </span>
                            @endif
                        
                        @if($alumni->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i> Unverified
                            </span>
                        @endif
                    </div>
                    
                    @if($alumni->employment_status)
                        <div class="mb-3">
                            @switch($alumni->employment_status)
                                @case('employed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-briefcase mr-1"></i> Employed
                                    </span>
                                    @break
                                @case('unemployed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-search mr-1"></i> Unemployed
                                    </span>
                                    @break
                                @case('self_employed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-user-tie mr-1"></i> Self-employed
                                    </span>
                                    @break
                                @case('student')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-graduation-cap mr-1"></i> Student
                                    </span>
                                    @break
                                @case('other')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-user mr-1"></i> Other
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    @endif
                    
                    @if($alumni->job_title && $alumni->current_employer)
                        <p class="text-sm text-gray-600 mb-3">
                            {{ $alumni->job_title }} at {{ $alumni->current_employer }}
                        </p>
                    @endif
                    
                    @if($alumni->linkedin_url)
                        <a href="{{ $alumni->linkedin_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fab fa-linkedin text-blue-600 mr-2"></i> LinkedIn Profile
                            </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Detailed Information Sections -->
        <div class="lg:col-span-3 space-y-4">
            <!-- Personal Information -->
            <div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center p-4 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-user text-blue-600 mr-3"></i>
                        <h3 class="text-base font-semibold">Personal Information</h3>
                    </div>
                    <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                
                <div x-show="open" class="border-t border-gray-200 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Full Name</h4>
                            <p>{{ $alumni->first_name }} {{ $alumni->last_name }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Email</h4>
                            <p>{{ $alumni->email }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Phone</h4>
                            <p>{{ $alumni->phone ?: 'Not provided' }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Address</h4>
                            <p>
                                @if($alumni->address)
                                    {{ $alumni->address }}<br>
                                    @if($alumni->city || $alumni->state || $alumni->zip)
                                        {{ $alumni->city ? $alumni->city . ', ' : '' }}
                                        {{ $alumni->state ? $alumni->state . ' ' : '' }}
                                        {{ $alumni->zip ?? '' }}<br>
                                    @endif
                                    {{ $alumni->country ?? '' }}
                                @else
                                    Not provided
                                @endif
                            </p>
                    </div>
                    </div>
                </div>
            </div>
            
            <!-- Academic Information -->
            <div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center p-4 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-purple-600 mr-3"></i>
                        <h3 class="text-base font-semibold">Academic Information</h3>
                </div>
                    <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                
                <div x-show="open" class="border-t border-gray-200 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Batch Year</h4>
                            <p>{{ $alumni->batch_year ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Graduation Date</h4>
                            <p>{{ $alumni->graduation_date ? $alumni->graduation_date->format('F d, Y') : 'Not provided' }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Department</h4>
                            <p>{{ $alumni->department ?: 'Not provided' }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Degree</h4>
                            <p>{{ $alumni->degree ?: 'Not provided' }}</p>
                    </div>
                    </div>
                </div>
            </div>
            
            <!-- Employment Information -->
            <div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center p-4 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-briefcase text-green-600 mr-3"></i>
                        <h3 class="text-base font-semibold">Employment Information</h3>
                </div>
                    <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                
                <div x-show="open" class="border-t border-gray-200 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Employment Status</h4>
                            <p>{{ $alumni->employment_status ? ucfirst(str_replace('_', '-', $alumni->employment_status)) : 'Not provided' }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Current Employer</h4>
                            <p>{{ $alumni->current_employer ?: 'Not provided' }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Job Title</h4>
                            <p>{{ $alumni->job_title ?: 'Not provided' }}</p>
                    </div>
                        <div>
                            <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">LinkedIn</h4>
                            <p>
                            @if($alumni->linkedin_url)
                                <a href="{{ $alumni->linkedin_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        View Profile
                                </a>
                            @else
                                Not provided
                            @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            @if($alumni->notes)
            <div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center p-4 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-sticky-note text-yellow-600 mr-3"></i>
                        <h3 class="text-base font-semibold">Notes</h3>
                    </div>
                    <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                    
                <div x-show="open" class="border-t border-gray-200 p-4">
                    <p class="text-gray-700">{{ $alumni->notes }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Employment History -->
            <div x-data="{ open: false }" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center p-4 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-briefcase text-blue-600 mr-3"></i>
                        <h3 class="text-base font-semibold">Employment History</h3>
                        @if($alumni->employmentHistories && $alumni->employmentHistories->count() > 0)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $alumni->employmentHistories->count() }} {{ $alumni->employmentHistories->count() === 1 ? 'job' : 'jobs' }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('alumni.employment-history.index', $alumni) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium px-3 py-1 rounded border border-blue-200 hover:border-blue-300"
                           onclick="event.stopPropagation();">
                            <i class="fas fa-external-link-alt mr-1"></i> Manage
                        </a>
                        <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </div>
                </button>
                    
                <div x-show="open" class="border-t border-gray-200 p-4">
                    @if($alumni->employmentHistories && $alumni->employmentHistories->count() > 0)
                        <div class="space-y-3">
                            @foreach($alumni->employmentHistories->take(3) as $employment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $employment->position }}</h4>
                                        <p class="text-sm text-blue-600">{{ $employment->company_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $employment->start_date->format('M Y') }} - 
                                            {{ $employment->end_date ? $employment->end_date->format('M Y') : 'Present' }}
                                            @if($employment->is_current)
                                                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Current</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($employment->location)
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $employment->location }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @if($alumni->employmentHistories->count() > 3)
                                <div class="text-center pt-2">
                                    <a href="{{ route('alumni.employment-history.index', $alumni) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View all {{ $alumni->employmentHistories->count() }} employment records <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="text-gray-400 mb-2">
                                <i class="fas fa-briefcase text-2xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm mb-3">No employment history recorded yet.</p>
                            @canany(['update'], $alumni)
                                <a href="{{ route('alumni.employment-history.create', $alumni) }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100">
                                    <i class="fas fa-plus mr-1"></i> Add Employment History
                                </a>
                            @endcanany
                        </div>
                    @endif
                </div>
            </div>

            <!-- Instructor Notes -->
            <div x-data="{ open: false }" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center p-4 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-clipboard-list text-purple-600 mr-3"></i>
                        <h3 class="text-base font-semibold">Instructor Notes</h3>
                        @if($alumni->instructorNotes)
                            @php
                                $visibleNotes = Auth::user() && in_array(Auth::user()->role, ['instructor', 'tenant_admin', 'central_admin']) 
                                    ? $alumni->instructorNotes 
                                    : $alumni->publicInstructorNotes;
                            @endphp
                            @if($visibleNotes->count() > 0)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $visibleNotes->count() }} {{ $visibleNotes->count() === 1 ? 'note' : 'notes' }}
                                </span>
                            @endif
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('alumni.instructor-notes.index', $alumni) }}" 
                           class="text-purple-600 hover:text-purple-800 text-sm font-medium px-3 py-1 rounded border border-purple-200 hover:border-purple-300"
                           onclick="event.stopPropagation();">
                            <i class="fas fa-external-link-alt mr-1"></i> View All
                        </a>
                        <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </div>
                </button>
                    
                <div x-show="open" class="border-t border-gray-200 p-4">
                    @if($alumni->instructorNotes)
                        @php
                            $visibleNotes = Auth::user() && in_array(Auth::user()->role, ['instructor', 'tenant_admin', 'central_admin']) 
                                ? $alumni->instructorNotes->take(3) 
                                : $alumni->publicInstructorNotes->take(3);
                        @endphp
                        @if($visibleNotes->count() > 0)
                            <div class="space-y-3">
                                @foreach($visibleNotes as $note)
                                    <div class="p-3 {{ $note->priority == 'high' ? 'bg-red-50 border-l-4 border-red-400' : ($note->priority == 'medium' ? 'bg-yellow-50 border-l-4 border-yellow-400' : 'bg-gray-50 border-l-4 border-gray-400') }} rounded-r-lg">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs font-medium text-gray-500 uppercase">{{ str_replace('_', ' ', $note->note_type) }}</span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $note->priority == 'high' ? 'bg-red-100 text-red-800' : ($note->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst($note->priority) }}
                                                </span>
                                                @if($note->is_private)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-lock mr-1"></i> Private
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $note->created_at->format('M j, Y') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($note->note, 150) }}</p>
                                        <div class="mt-2 text-xs text-gray-500">
                                            by {{ $note->instructor->name }}
                                        </div>
                                    </div>
                                @endforeach
                                @php
                                    $totalVisible = Auth::user() && in_array(Auth::user()->role, ['instructor', 'tenant_admin', 'central_admin']) 
                                        ? $alumni->instructorNotes->count() 
                                        : $alumni->publicInstructorNotes->count();
                                @endphp
                                @if($totalVisible > 3)
                                    <div class="text-center pt-2">
                                        <a href="{{ route('alumni.instructor-notes.index', $alumni) }}" 
                                           class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                            View all {{ $totalVisible }} notes <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="text-gray-400 mb-2">
                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm mb-3">No instructor notes available.</p>
                                @can('create', App\Models\InstructorNote::class)
                                    <a href="{{ route('alumni.instructor-notes.create', $alumni) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-purple-600 bg-purple-50 border border-purple-200 rounded-md hover:bg-purple-100">
                                        <i class="fas fa-plus mr-1"></i> Add Instructor Note
                                    </a>
                                @endcan
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <div class="text-gray-400 mb-2">
                                <i class="fas fa-clipboard-list text-2xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm mb-3">No instructor notes available.</p>
                            @can('create', App\Models\InstructorNote::class)
                                <a href="{{ route('alumni.instructor-notes.create', $alumni) }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-purple-600 bg-purple-50 border border-purple-200 rounded-md hover:bg-purple-100">
                                    <i class="fas fa-plus mr-1"></i> Add Instructor Note
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Skills & Achievements -->
            <div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center p-4 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-trophy text-amber-600 mr-3"></i>
                        <h3 class="text-base font-semibold">Skills & Achievements</h3>
                    </div>
                    <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                    
                <div x-show="open" class="border-t border-gray-200 p-4">
                    <p class="text-gray-700 italic">No skills or achievements recorded yet.</p>
                </div>
                    </div>
                </div>
            </div>
            
    <!-- Delete Button Section -->
    <div class="mt-8 text-right">
        <form id="delete-form" action="{{ route('alumni.destroy', $alumni->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
            <button 
                type="button" 
                class="btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                    detail: {
                        title: 'Delete Alumni Record',
                        message: 'Are you sure you want to delete this alumni record? This action cannot be undone.',
                        type: 'danger',
                        confirmButtonText: 'Delete',
                        onConfirm: () => document.getElementById('delete-form').submit()
                    }
                }))"
            >
                        <i class="fas fa-trash mr-2"></i> Delete Record
                    </button>
                </form>
    </div>
</div>
@endsection 

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endpush 
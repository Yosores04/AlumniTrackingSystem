@extends('layouts.instructor')

@section('title', 'Alumni Details')

@section('content')
<div class="content-card">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold section-heading">Alumni Details</h2>
        <div class="flex space-x-3">
            <a href="{{ route('instructor.alumni.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
            <a href="{{ route('instructor.alumni.edit', $alumni->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg p-1.5" data-dismiss-target="alert" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Profile Overview Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                <div class="px-4 pt-0 pb-6 text-center">
                    <div class="-mt-12 mb-4">
                        @if($alumni->profile_photo_url)
                            <img src="{{ $alumni->profile_photo_url }}" alt="{{ $alumni->name }}" 
                                class="w-24 h-24 rounded-full border-4 border-white mx-auto object-cover shadow-md">
                        @elseif($alumni->profile_photo_path)
                            <img src="{{ Storage::url($alumni->profile_photo_path) }}" alt="{{ $alumni->name }}" 
                                class="w-24 h-24 rounded-full border-4 border-white mx-auto object-cover shadow-md">
                        @else
                            <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center border-4 border-white mx-auto shadow-md">
                                <span class="text-xl font-bold">{{ substr($alumni->first_name, 0, 1) . substr($alumni->last_name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $alumni->name }}</h3>
                    <p class="text-gray-500 text-sm mb-2">{{ $alumni->email }}</p>
                    
                    <div class="flex justify-center flex-wrap gap-2 mb-3">
                        @if($alumni->batch_year)
                            <span class="badge bg-blue-100 text-blue-800">{{ $alumni->batch_year }} Batch</span>
                        @endif
                        
                        @if($alumni->is_verified)
                            <span class="badge bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                        @else
                            <span class="badge bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i> Unverified
                            </span>
                        @endif
                        
                        @if($alumni->employment_status)
                            @switch($alumni->employment_status)
                                @case('employed')
                                    <span class="badge bg-green-100 text-green-800">Employed</span>
                                    @break
                                @case('unemployed')
                                    <span class="badge bg-red-100 text-red-800">Unemployed</span>
                                    @break
                                @case('self_employed')
                                    <span class="badge bg-blue-100 text-blue-800">Self-employed</span>
                                    @break
                                @case('student')
                                    <span class="badge bg-purple-100 text-purple-800">Student</span>
                                    @break
                                @case('other')
                                    <span class="badge bg-gray-100 text-gray-800">Other</span>
                                    @break
                            @endswitch
                        @endif
                    </div>
                    
                    @if($alumni->current_employer)
                        <p class="text-sm text-gray-600 mb-3">
                            {{ $alumni->job_title ? $alumni->job_title . ' at ' : '' }}{{ $alumni->current_employer }}
                        </p>
                    @endif
                    
                    @if($alumni->linkedin_url)
                        <a href="{{ $alumni->linkedin_url }}" class="inline-flex items-center text-sm px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50" target="_blank">
                            <i class="fab fa-linkedin text-blue-600 mr-2"></i> LinkedIn Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detailed Information with Accordions -->
        <div class="lg:col-span-3">
            <div x-data="{ 
                active: 'personal',
                toggle(section) {
                    this.active = this.active === section ? null : section
                },
                isOpen(section) {
                    return this.active === section
                }
             }" class="space-y-4">
                
                <!-- Personal Information Accordion -->
                <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                    <button @click="toggle('personal')" class="w-full flex justify-between items-center p-4 focus:outline-none">
                        <div class="flex items-center">
                            <i class="fas fa-user text-blue-600 mr-3"></i>
                            <h3 class="text-base font-semibold">Personal Information</h3>
                        </div>
                        <i class="fas transition-transform duration-300 ease-in-out" :class="isOpen('personal') ? 'fa-chevron-up rotate-0' : 'fa-chevron-down rotate-180'"></i>
                    </button>
                    
                    <div x-show="isOpen('personal')" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4">
                        <div class="border-t border-gray-200 p-4">
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
                                    <p>{{ $alumni->phone ?? 'Not provided' }}</p>
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
                </div>
                
                <!-- Academic Information Accordion -->
                <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                    <button @click="toggle('academic')" class="w-full flex justify-between items-center p-4 focus:outline-none">
                        <div class="flex items-center">
                            <i class="fas fa-graduation-cap text-purple-600 mr-3"></i>
                            <h3 class="text-base font-semibold">Academic Information</h3>
                        </div>
                        <i class="fas transition-transform duration-300 ease-in-out" :class="isOpen('academic') ? 'fa-chevron-up rotate-0' : 'fa-chevron-down rotate-180'"></i>
                    </button>
                    
                    <div x-show="isOpen('academic')" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4">
                        <div class="border-t border-gray-200 p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Batch Year</h4>
                                    <p>{{ $alumni->batch_year ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Graduation Date</h4>
                                    <p>{{ $alumni->graduation_date ? $alumni->graduation_date->format('F d, Y') : 'Not provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Department</h4>
                                    <p>{{ $alumni->department ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Degree</h4>
                                    <p>{{ $alumni->degree ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Employment Information Accordion -->
                <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                    <button @click="toggle('employment')" class="w-full flex justify-between items-center p-4 focus:outline-none">
                        <div class="flex items-center">
                            <i class="fas fa-briefcase text-green-600 mr-3"></i>
                            <h3 class="text-base font-semibold">Employment Information</h3>
                        </div>
                        <i class="fas transition-transform duration-300 ease-in-out" :class="isOpen('employment') ? 'fa-chevron-up rotate-0' : 'fa-chevron-down rotate-180'"></i>
                    </button>
                    
                    <div x-show="isOpen('employment')" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4">
                        <div class="border-t border-gray-200 p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Employment Status</h4>
                                    <p>
                                        @switch($alumni->employment_status)
                                            @case('employed')
                                                Employed
                                                @break
                                            @case('unemployed')
                                                Unemployed
                                                @break
                                            @case('self_employed')
                                                Self-employed
                                                @break
                                            @case('student')
                                                Student
                                                @break
                                            @case('other')
                                                Other
                                                @break
                                            @default
                                                Not provided
                                        @endswitch
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Current Employer</h4>
                                    <p>{{ $alumni->current_employer ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Job Title</h4>
                                    <p>{{ $alumni->job_title ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-1">Industry</h4>
                                    <p>{{ $alumni->industry ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Skills & Achievements Accordion -->
                <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                    <button @click="toggle('skills')" class="w-full flex justify-between items-center p-4 focus:outline-none">
                        <div class="flex items-center">
                            <i class="fas fa-trophy text-amber-600 mr-3"></i>
                            <h3 class="text-base font-semibold">Skills & Achievements</h3>
                        </div>
                        <i class="fas transition-transform duration-300 ease-in-out" :class="isOpen('skills') ? 'fa-chevron-up rotate-0' : 'fa-chevron-down rotate-180'"></i>
                    </button>
                    
                    <div x-show="isOpen('skills')" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4">
                        <div class="border-t border-gray-200 p-4">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-2">Skills</h4>
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
                                        <p class="text-gray-600">No skills provided</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-2">Achievements</h4>
                                    @if($alumni->achievements)
                                        <p class="text-gray-600">{{ $alumni->achievements }}</p>
                                    @else
                                        <p class="text-gray-600">No achievements provided</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="text-xs font-semibold uppercase text-gray-500 mb-2">Certifications</h4>
                                    @if($alumni->certifications)
                                        <p class="text-gray-600">{{ $alumni->certifications }}</p>
                                    @else
                                        <p class="text-gray-600">No certifications provided</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notes Accordion -->
                <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                    <button @click="toggle('notes')" class="w-full flex justify-between items-center p-4 focus:outline-none">
                        <div class="flex items-center">
                            <i class="fas fa-sticky-note text-yellow-600 mr-3"></i>
                            <h3 class="text-base font-semibold">Notes</h3>
                        </div>
                        <i class="fas transition-transform duration-300 ease-in-out" :class="isOpen('notes') ? 'fa-chevron-up rotate-0' : 'fa-chevron-down rotate-180'"></i>
                    </button>
                    
                    <div x-show="isOpen('notes')" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4">
                        <div class="border-t border-gray-200 p-4">
                            @if($alumni->notes)
                                <p class="text-gray-600 whitespace-pre-line">{{ $alumni->notes }}</p>
                            @else
                                <p class="text-gray-600">No notes have been added for this alumni.</p>
                            @endif
                            
                            <!-- Timestamps - When the record was created and last updated -->
                            <x-timestamps :model="$alumni" />
                        </div>
                    </div>
                </div>
                
                <!-- Contact History Accordion -->
                <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                    <button @click="toggle('contact')" class="w-full flex justify-between items-center p-4 focus:outline-none">
                        <div class="flex items-center">
                            <i class="fas fa-history text-indigo-600 mr-3"></i>
                            <h3 class="text-base font-semibold">Contact History</h3>
                        </div>
                        <i class="fas transition-transform duration-300 ease-in-out" :class="isOpen('contact') ? 'fa-chevron-up rotate-0' : 'fa-chevron-down rotate-180'"></i>
                    </button>
                    
                    <div x-show="isOpen('contact')" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4">
                        <div class="border-t border-gray-200 p-4">
                            @if(isset($contactHistory) && count($contactHistory) > 0)
                                <div class="space-y-4">
                                    @foreach($contactHistory as $contact)
                                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                                            <div class="flex justify-between">
                                                <p class="font-medium">{{ $contact->type }}</p>
                                                <p class="text-sm text-gray-500">{{ $contact->created_at->format('M d, Y') }}</p>
                                            </div>
                                            <p class="text-sm mt-1">{{ $contact->notes }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-4">
                                    <p>No contact history available</p>
                                </div>
                            @endif
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-plus mr-2"></i> Add Contact Record
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Delete Record Button -->
            <div class="mt-6 text-right">
                <form id="delete-form" action="{{ route('instructor.alumni.destroy', $alumni->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="button" 
                        class="btn bg-red-500 hover:bg-red-600 text-white"
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
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('title', 'Alumni Details')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Alumni Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('alumni.edit', $alumni->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('alumni.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Profile Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-primary-80 h-24"></div>
                <div class="p-6 -mt-12 text-center">
                    <div class="avatar-lg mx-auto mb-4 ring-4 ring-white">
                        @if($alumni->profile_photo_path)
                            <img src="{{ Storage::url($alumni->profile_photo_path) }}" alt="{{ $alumni->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                <i class="fas fa-user text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $alumni->name }}</h2>
                    <p class="text-gray-500 mb-4">{{ $alumni->email }}</p>
                    
                    <div class="flex flex-col space-y-2">
                        <div class="flex justify-center space-x-1">
                            <span class="badge {{ $alumni->is_verified ? 'badge-success' : 'badge-warning' }}">
                                <i class="fas {{ $alumni->is_verified ? 'fa-check-circle' : 'fa-exclamation-triangle' }} mr-1"></i>
                                {{ $alumni->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                            @if($alumni->batch_year)
                                <span class="badge badge-info">{{ $alumni->batch_year }} Batch</span>
                            @endif
                        </div>
                        
                        @if($alumni->employment_status)
                            <span class="badge {{ $alumni->employment_status == 'employed' || $alumni->employment_status == 'self_employed' ? 'badge-success' : ($alumni->employment_status == 'student' ? 'badge-info' : 'badge-warning') }}">
                                {{ ucfirst(str_replace('_', '-', $alumni->employment_status)) }}
                            </span>
                        @endif
                    </div>
                    
                    @if($alumni->linkedin_url)
                        <div class="mt-4">
                            <a href="{{ $alumni->linkedin_url }}" target="_blank" class="btn btn-secondary w-full">
                                <i class="fab fa-linkedin mr-2"></i> LinkedIn Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Details Section -->
        <div class="md:col-span-3 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="section-header">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-accent"></i> Personal Information
                    </h3>
                </div>
                
                <div class="section-divider"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">First Name</span>
                        <span class="font-medium">{{ $alumni->first_name }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Last Name</span>
                        <span class="font-medium">{{ $alumni->last_name }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Email</span>
                        <span class="font-medium">{{ $alumni->email }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Phone</span>
                        <span class="font-medium">{{ $alumni->phone ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col md:col-span-2">
                        <span class="text-sm text-gray-500">Address</span>
                        <span class="font-medium">{{ $alumni->address ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">City</span>
                        <span class="font-medium">{{ $alumni->city ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">State/Province</span>
                        <span class="font-medium">{{ $alumni->state ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">ZIP/Postal Code</span>
                        <span class="font-medium">{{ $alumni->zip ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Country</span>
                        <span class="font-medium">{{ $alumni->country ?: 'Not provided' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Academic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="section-header">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-accent"></i> Academic Information
                    </h3>
                </div>
                
                <div class="section-divider"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Batch Year</span>
                        <span class="font-medium">{{ $alumni->batch_year ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Graduation Date</span>
                        <span class="font-medium">
                            {{ $alumni->graduation_date ? $alumni->graduation_date->format('F j, Y') : 'Not provided' }}
                        </span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Department</span>
                        <span class="font-medium">{{ $alumni->department ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Degree</span>
                        <span class="font-medium">{{ $alumni->degree ?: 'Not provided' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Employment Information -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="section-header">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-briefcase mr-2 text-accent"></i> Employment Information
                    </h3>
                </div>
                
                <div class="section-divider"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Employment Status</span>
                        <span class="font-medium">
                            @if($alumni->employment_status)
                                <span class="badge {{ $alumni->employment_status == 'employed' || $alumni->employment_status == 'self_employed' ? 'badge-success' : ($alumni->employment_status == 'student' ? 'badge-info' : 'badge-warning') }}">
                                    {{ ucfirst(str_replace('_', '-', $alumni->employment_status)) }}
                                </span>
                            @else
                                Not provided
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Current Employer</span>
                        <span class="font-medium">{{ $alumni->current_employer ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">Job Title</span>
                        <span class="font-medium">{{ $alumni->job_title ?: 'Not provided' }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">LinkedIn</span>
                        <span class="font-medium">
                            @if($alumni->linkedin_url)
                                <a href="{{ $alumni->linkedin_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $alumni->linkedin_url }}
                                </a>
                            @else
                                Not provided
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            @if($alumni->notes)
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="section-header">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-sticky-note mr-2 text-accent"></i> Notes
                        </h3>
                    </div>
                    
                    <div class="section-divider"></div>
                    
                    <div class="prose max-w-none">
                        {{ $alumni->notes }}
                    </div>
                </div>
            @endif
            
            <!-- Record Information -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="section-header">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-accent"></i> Record Information
                    </h3>
                </div>
                
                <div class="section-divider"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div class="flex flex-col">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium">{{ $alumni->created_at->format('F j, Y g:i A') }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-gray-500">Last Updated</span>
                        <span class="font-medium">{{ $alumni->updated_at->format('F j, Y g:i A') }}</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-gray-500">Record ID</span>
                        <span class="font-medium">{{ $alumni->id }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Delete Button -->
            <div class="flex justify-end">
                <form action="{{ route('alumni.destroy', $alumni->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this alumni record?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i> Delete Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
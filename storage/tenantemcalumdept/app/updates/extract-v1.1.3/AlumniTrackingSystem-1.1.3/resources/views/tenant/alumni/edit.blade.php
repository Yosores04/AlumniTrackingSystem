@extends('layouts.app')

@section('title', 'Edit Alumni')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Alumni: {{ $alumni->name }}</h1>
        <div class="flex items-center space-x-3">
            <a href="{{ route('alumni.show', $alumni->id) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-eye mr-1"></i> View Details
            </a>
            <a href="{{ route('alumni.index') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 border-l-4 border-red-500">
            <div class="font-medium flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> Please fix the following errors:
            </div>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('alumni.update', $alumni->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information Section -->
            <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                <h2 class="font-semibold text-lg mb-6 flex items-center">
                    <i class="fas fa-user mr-2 text-gray-700"></i> Personal Information
                </h2>
                
                <div class="mb-4">
                    <label for="first_name" class="block text-sm text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="first_name" name="first_name" value="{{ old('first_name', $alumni->first_name) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="last_name" class="block text-sm text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="last_name" name="last_name" value="{{ old('last_name', $alumni->last_name) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="email" name="email" value="{{ old('email', $alumni->email) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="phone" class="block text-sm text-gray-700 mb-1">Phone Number</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="phone" name="phone" value="{{ old('phone', $alumni->phone) }}">
                </div>
                
                <div class="mb-4">
                    <label for="address" class="block text-sm text-gray-700 mb-1">Address</label>
                    <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                              id="address" name="address" rows="2">{{ old('address', $alumni->address) }}</textarea>
                </div>
                
                <div class="mb-4">
                    <label for="city" class="block text-sm text-gray-700 mb-1">City</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="city" name="city" value="{{ old('city', $alumni->city) }}">
                </div>
                
                <div class="mb-4">
                    <label for="state" class="block text-sm text-gray-700 mb-1">State/Province</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="state" name="state" value="{{ old('state', $alumni->state) }}">
                </div>
                
                <div class="mb-4">
                    <label for="zip" class="block text-sm text-gray-700 mb-1">ZIP/Postal Code</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="zip" name="zip" value="{{ old('zip', $alumni->zip) }}">
                </div>
                
                <div class="mb-4">
                    <label for="country" class="block text-sm text-gray-700 mb-1">Country</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="country" name="country" value="{{ old('country', $alumni->country) }}">
                </div>
            </div>
            
            <!-- Academic & Employment Information Section -->
            <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                <h2 class="font-semibold text-lg mb-6 flex items-center">
                    <i class="fas fa-graduation-cap mr-2 text-gray-700"></i> Academic & Employment Information
                </h2>
                
                <div class="mb-4">
                    <label for="batch_year" class="block text-sm text-gray-700 mb-1">Batch Year</label>
                    <input type="number" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="batch_year" name="batch_year" value="{{ old('batch_year', $alumni->batch_year) }}" 
                           min="1950" max="{{ date('Y') + 5 }}">
                </div>
                
                <div class="mb-4">
                    <label for="graduation_date" class="block text-sm text-gray-700 mb-1">Graduation Date</label>
                    <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="graduation_date" name="graduation_date" 
                           value="{{ old('graduation_date', $alumni->graduation_date ? $alumni->graduation_date->format('Y-m-d') : '') }}">
                </div>
                
                <div class="mb-4">
                    <label for="department" class="block text-sm text-gray-700 mb-1">Department</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="department" name="department" value="{{ old('department', $alumni->department) }}">
                </div>
                
                <div class="mb-4">
                    <label for="degree" class="block text-sm text-gray-700 mb-1">Degree</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="degree" name="degree" value="{{ old('degree', $alumni->degree) }}">
                </div>
                
                <div class="mb-4">
                    <label for="employment_status" class="block text-sm text-gray-700 mb-1">Employment Status</label>
                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                            id="employment_status" name="employment_status">
                        <option value="">Select Status</option>
                        <option value="employed" {{ old('employment_status', $alumni->employment_status) == 'employed' ? 'selected' : '' }}>Employed</option>
                        <option value="unemployed" {{ old('employment_status', $alumni->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                        <option value="self_employed" {{ old('employment_status', $alumni->employment_status) == 'self_employed' ? 'selected' : '' }}>Self-employed</option>
                        <option value="student" {{ old('employment_status', $alumni->employment_status) == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="other" {{ old('employment_status', $alumni->employment_status) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="current_employer" class="block text-sm text-gray-700 mb-1">Current Employer</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="current_employer" name="current_employer" value="{{ old('current_employer', $alumni->current_employer) }}">
                </div>
                
                <div class="mb-4">
                    <label for="job_title" class="block text-sm text-gray-700 mb-1">Job Title</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="job_title" name="job_title" value="{{ old('job_title', $alumni->job_title) }}">
                </div>
                
                <div class="mb-4">
                    <label for="linkedin_url" class="block text-sm text-gray-700 mb-1">LinkedIn URL</label>
                    <input type="url" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $alumni->linkedin_url) }}">
                </div>
                
                <div class="mb-4">
                    <label for="profile_photo_url" class="block text-sm text-gray-700 mb-1">Profile Photo URL</label>
                    
                    <div class="mt-2 mb-3">
                        <!-- Current photo preview -->
                        @if($alumni->profile_photo_path)
                            <div class="mb-2 flex items-center">
                                <img src="{{ Storage::url($alumni->profile_photo_path) }}" alt="{{ $alumni->name }}" class="w-12 h-12 rounded-full object-cover mr-2">
                                <span class="text-sm text-gray-600">Current profile photo (uploaded file)</span>
                            </div>
                        @elseif($alumni->profile_photo_url)
                            <div class="mb-2 flex items-center">
                                <img src="{{ $alumni->profile_photo_url }}" alt="{{ $alumni->name }}" class="w-12 h-12 rounded-full object-cover mr-2">
                                <span class="text-sm text-gray-600">Current profile photo (URL)</span>
                            </div>
                        @endif
                    </div>
                    
                    <input type="url" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                        id="profile_photo_url" name="profile_photo_url" value="{{ old('profile_photo_url', $alumni->profile_photo_url) }}" 
                        placeholder="https://example.com/photo.jpg">
                    <p class="mt-1 text-xs text-gray-500">Enter a direct URL to an image</p>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm text-gray-700 mb-1">Notes</label>
                    <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" 
                              id="notes" name="notes" rows="3">{{ old('notes', $alumni->notes) }}</textarea>
                </div>
                
                <div class="flex items-center mb-4">
                    <input class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                           type="checkbox" id="is_verified" name="is_verified" value="1"
                           {{ old('is_verified', $alumni->is_verified) ? 'checked' : '' }}>
                    <label class="text-sm text-gray-700" for="is_verified">Verified Alumni</label>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                Update Alumni Record
            </button>
        </div>
    </form>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
@endsection 
@extends('layouts.instructor')

@section('title', 'Edit Alumni')

@section('content')
<div class="content-card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Alumni: {{ $alumni->name }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('instructor.alumni.show', $alumni->id) }}" class="btn btn-secondary">
                <i class="fas fa-eye mr-2"></i> View Details
            </a>
            <a href="{{ route('instructor.alumni.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 border border-red-200">
            <div class="font-medium">Please fix the following errors:</div>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('instructor.alumni.update', $alumni->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Personal Information -->
            <div>
                <h2 class="section-heading flex items-center">
                    <i class="fas fa-user mr-2 text-accent"></i> Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="first_name" class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $alumni->first_name) }}" required>
                    </div>
                    <div>
                        <label for="last_name" class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $alumni->last_name) }}" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $alumni->email) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $alumni->phone) }}">
                </div>
                
                <div class="mb-4">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $alumni->address) }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $alumni->city) }}">
                    </div>
                    <div>
                        <label for="state" class="form-label">State/Province</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $alumni->state) }}">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="zip" class="form-label">ZIP/Postal Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip', $alumni->zip) }}">
                    </div>
                    <div>
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $alumni->country) }}">
                    </div>
                </div>
            </div>
            
            <!-- Academic & Employment Information -->
            <div>
                <h2 class="section-heading flex items-center">
                    <i class="fas fa-graduation-cap mr-2 text-accent"></i> Academic & Employment Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="batch_year" class="form-label">Batch Year</label>
                        <input type="number" class="form-control" id="batch_year" name="batch_year" 
                               value="{{ old('batch_year', $alumni->batch_year) }}" 
                               min="1950" max="{{ date('Y') + 5 }}">
                    </div>
                    <div>
                        <label for="graduation_date" class="form-label">Graduation Date</label>
                        <input type="date" class="form-control" id="graduation_date" name="graduation_date" 
                               value="{{ old('graduation_date', $alumni->graduation_date ? $alumni->graduation_date->format('Y-m-d') : '') }}">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" value="{{ old('department', $alumni->department) }}">
                    </div>
                    <div>
                        <label for="degree" class="form-label">Degree</label>
                        <input type="text" class="form-control" id="degree" name="degree" value="{{ old('degree', $alumni->degree) }}">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="employment_status" class="form-label">Employment Status</label>
                    <select class="form-control" id="employment_status" name="employment_status">
                        <option value="">Select Status</option>
                        <option value="employed" {{ old('employment_status', $alumni->employment_status) == 'employed' ? 'selected' : '' }}>Employed</option>
                        <option value="unemployed" {{ old('employment_status', $alumni->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                        <option value="self_employed" {{ old('employment_status', $alumni->employment_status) == 'self_employed' ? 'selected' : '' }}>Self-employed</option>
                        <option value="student" {{ old('employment_status', $alumni->employment_status) == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="other" {{ old('employment_status', $alumni->employment_status) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="current_employer" class="form-label">Current Employer</label>
                    <input type="text" class="form-control" id="current_employer" name="current_employer" value="{{ old('current_employer', $alumni->current_employer) }}">
                </div>
                
                <div class="mb-4">
                    <label for="job_title" class="form-label">Job Title</label>
                    <input type="text" class="form-control" id="job_title" name="job_title" value="{{ old('job_title', $alumni->job_title) }}">
                </div>
                
                <div class="mb-4">
                    <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $alumni->linkedin_url) }}">
                </div>
                
                <div class="mb-4">
                    <label for="profile_photo_url" class="form-label">Profile Photo URL</label>
                    <div class="mb-2">
                        @if($alumni->profile_photo_path)
                            <div class="mb-2 flex items-center">
                                <img src="{{ Storage::url($alumni->profile_photo_path) }}" alt="{{ $alumni->name }}" class="w-16 h-16 rounded-full object-cover">
                                <span class="ml-2 text-sm text-gray-600">Current profile photo (uploaded file)</span>
                            </div>
                        @elseif($alumni->profile_photo_url)
                            <div class="mb-2 flex items-center">
                                <img src="{{ $alumni->profile_photo_url }}" alt="{{ $alumni->name }}" class="w-16 h-16 rounded-full object-cover">
                                <span class="ml-2 text-sm text-gray-600">Current profile photo (URL)</span>
                            </div>
                        @endif
                    </div>
                    <input type="url" class="form-control" id="profile_photo_url" name="profile_photo_url" value="{{ old('profile_photo_url', $alumni->profile_photo_url) }}" placeholder="https://example.com/photo.jpg">
                    <p class="mt-1 text-sm text-gray-500">Enter a direct URL to an image</p>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $alumni->notes) }}</textarea>
                </div>
                
                <div class="flex items-center mb-4">
                    <input class="mr-2 h-4 w-4 text-accent focus:ring-accent border-gray-300 rounded" type="checkbox" id="is_verified" name="is_verified" value="1"
                           {{ old('is_verified', $alumni->is_verified) ? 'checked' : '' }}>
                    <label class="form-label mb-0" for="is_verified">Verified Alumni</label>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('instructor.alumni.show', $alumni->id) }}" class="btn btn-secondary mr-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Alumni Record</button>
        </div>
    </form>
</div>
@endsection 
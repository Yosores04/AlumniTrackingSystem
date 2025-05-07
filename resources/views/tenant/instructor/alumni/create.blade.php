@extends('layouts.instructor')

@section('title', 'Add New Alumni')

@section('content')
<div class="content-card">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold section-heading">Add New Alumni</h2>
        <a href="{{ route('instructor.alumni.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 border border-red-200">
            <div class="font-medium flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Please fix the following errors:
            </div>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="info-card mb-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
            <div>
                <p class="font-medium">Adding a new alumni record</p>
                <p class="text-sm mt-1">Fill in the form below to add a new alumni to the system. Fields marked with an asterisk (*) are required.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('instructor.alumni.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Personal Information -->
            <div>
                <div class="bg-gray-50 p-4 rounded-lg mb-5 border border-gray-100">
                    <h3 class="text-gray-700 font-medium flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Personal Information
                    </h3>
                </div>
                
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="first_name" class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div>
                        <label for="last_name" class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                </div>
                
                <div class="mb-5">
                    <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                
                <div class="mb-5">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                
                <div class="mb-5">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                    </div>
                    <div>
                        <label for="state" class="form-label">State/Province</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="zip" class="form-label">ZIP/Postal Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}">
                    </div>
                    <div>
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}">
                    </div>
                </div>
            </div>
            
            <!-- Academic & Employment Information -->
            <div>
                <div class="bg-gray-50 p-4 rounded-lg mb-5 border border-gray-100">
                    <h3 class="text-gray-700 font-medium flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                        Academic & Employment Information
                    </h3>
                </div>
                
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="batch_year" class="form-label">Batch Year</label>
                        <input type="number" class="form-control" id="batch_year" name="batch_year" value="{{ old('batch_year') }}" min="1950" max="{{ date('Y') + 5 }}">
                    </div>
                    <div>
                        <label for="graduation_date" class="form-label">Graduation Date</label>
                        <input type="date" class="form-control" id="graduation_date" name="graduation_date" value="{{ old('graduation_date') }}">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}">
                    </div>
                    <div>
                        <label for="degree" class="form-label">Degree</label>
                        <input type="text" class="form-control" id="degree" name="degree" value="{{ old('degree') }}">
                    </div>
                </div>
                
                <div class="mb-5">
                    <label for="employment_status" class="form-label">Employment Status</label>
                    <select class="form-control" id="employment_status" name="employment_status">
                        <option value="">Select Status</option>
                        <option value="employed" {{ old('employment_status') == 'employed' ? 'selected' : '' }}>Employed</option>
                        <option value="unemployed" {{ old('employment_status') == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                        <option value="self_employed" {{ old('employment_status') == 'self_employed' ? 'selected' : '' }}>Self-employed</option>
                        <option value="student" {{ old('employment_status') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="other" {{ old('employment_status') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div class="mb-5">
                    <label for="current_employer" class="form-label">Current Employer</label>
                    <input type="text" class="form-control" id="current_employer" name="current_employer" value="{{ old('current_employer') }}">
                </div>
                
                <div class="mb-5">
                    <label for="job_title" class="form-label">Job Title</label>
                    <input type="text" class="form-control" id="job_title" name="job_title" value="{{ old('job_title') }}">
                </div>
                
                <div class="mb-5">
                    <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}">
                </div>
                
                <div class="mb-5">
                    <label for="profile_photo_url" class="form-label">Profile Photo URL</label>
                    <input type="url" class="form-control" id="profile_photo_url" name="profile_photo_url" value="{{ old('profile_photo_url') }}" placeholder="https://example.com/photo.jpg">
                    <p class="text-xs text-gray-500 mt-1">Enter a direct URL to an image</p>
                </div>
                
                <div class="mb-5">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center mb-5">
                    <input type="checkbox" id="is_verified" name="is_verified" value="1" class="h-4 w-4 text-blue-600">
                    <label for="is_verified" class="ml-2 form-label mb-0">Mark as verified alumni</label>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <button type="button" class="btn btn-secondary mr-3" onclick="window.location.href='{{ route('instructor.alumni.index') }}'">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary py-2.5 px-6">
                <i class="fas fa-save mr-2"></i> Create Alumni Record
            </button>
        </div>
    </form>
</div>
@endsection 
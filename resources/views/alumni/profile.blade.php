@extends('layouts.alumni')

@section('title', 'Edit Alumni Profile')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Edit Your Profile</h2>
                    <a href="{{ route('alumni.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
                
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if (isset($readonly) && $readonly)
                    <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Account Pending Verification</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Your alumni profile is pending verification by an administrator. You can view your profile information, but editing is restricted until your account is verified.</p>
                                    <p class="mt-2">
                                        <a href="{{ route('support.create') }}" class="font-medium text-yellow-800 hover:text-yellow-900">
                                            Contact support <span aria-hidden="true">&rarr;</span>
                                        </a> if you have any questions.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('alumni.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Profile Photo -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                        <div class="flex items-center">
                            @if($alumni->profile_photo_path)
                                <div class="mr-4">
                                    <img src="{{ Storage::url($alumni->profile_photo_path) }}" alt="{{ $alumni->name }}" class="h-20 w-20 rounded-full object-cover">
                                </div>
                            @else
                                <div class="mr-4 h-20 w-20 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <span class="text-2xl font-bold">{{ substr($alumni->first_name, 0, 1) . substr($alumni->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Change Photo</label>
                                <input type="file" name="profile_photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" {{ isset($readonly) && $readonly ? 'disabled' : '' }}>
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Information -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('first_name', $alumni->first_name) }}" required {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('last_name', $alumni->last_name) }}" required {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100" value="{{ $alumni->email }}" readonly>
                                <p class="mt-1 text-xs text-gray-500">Email cannot be changed. Contact administrators if you need to update your email.</p>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="phone" id="phone" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('phone', $alumni->phone) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" name="address" id="address" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('address', $alumni->address) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="city" id="city" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('city', $alumni->city) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                <input type="text" name="state" id="state" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('state', $alumni->state) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="zip" class="block text-sm font-medium text-gray-700 mb-1">ZIP/Postal Code</label>
                                <input type="text" name="zip" id="zip" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('zip', $alumni->zip) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('zip')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input type="text" name="country" id="country" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('country', $alumni->country) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Information -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="batch_year" class="block text-sm font-medium text-gray-700 mb-1">Batch Year</label>
                                <input type="number" name="batch_year" id="batch_year" min="1950" max="{{ date('Y') + 5 }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('batch_year', $alumni->batch_year) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('batch_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="graduation_date" class="block text-sm font-medium text-gray-700 mb-1">Graduation Date</label>
                                <input type="date" name="graduation_date" id="graduation_date" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('graduation_date', $alumni->graduation_date ? $alumni->graduation_date->format('Y-m-d') : '') }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('graduation_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <input type="text" name="department" id="department" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('department', $alumni->department) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('department')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="degree" class="block text-sm font-medium text-gray-700 mb-1">Degree</label>
                                <input type="text" name="degree" id="degree" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('degree', $alumni->degree) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('degree')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Employment Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Employment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="employment_status" class="block text-sm font-medium text-gray-700 mb-1">Employment Status</label>
                                <select name="employment_status" id="employment_status" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" {{ isset($readonly) && $readonly ? 'disabled' : '' }}>
                                    <option value="">Select Status</option>
                                    <option value="employed" {{ old('employment_status', $alumni->employment_status) == 'employed' ? 'selected' : '' }}>Employed</option>
                                    <option value="unemployed" {{ old('employment_status', $alumni->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="self_employed" {{ old('employment_status', $alumni->employment_status) == 'self_employed' ? 'selected' : '' }}>Self-employed</option>
                                    <option value="student" {{ old('employment_status', $alumni->employment_status) == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="other" {{ old('employment_status', $alumni->employment_status) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('employment_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="current_employer" class="block text-sm font-medium text-gray-700 mb-1">Current Employer</label>
                                <input type="text" name="current_employer" id="current_employer" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('current_employer', $alumni->current_employer) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('current_employer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                                <input type="text" name="job_title" id="job_title" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('job_title', $alumni->job_title) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('job_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                                <input type="url" name="linkedin_url" id="linkedin_url" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" value="{{ old('linkedin_url', $alumni->linkedin_url) }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>
                                @error('linkedin_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Skills & Achievements -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Skills & Achievements</h3>
                        
                        <div class="mb-4">
                            <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                            <p class="text-xs text-gray-500 mb-2">List your skills, separated by commas (e.g., JavaScript, Project Management, Data Analysis)</p>
                            <textarea name="skills" id="skills" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>{{ old('skills', $alumni->skills) }}</textarea>
                            @error('skills')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="achievements" class="block text-sm font-medium text-gray-700 mb-1">Achievements</label>
                            <p class="text-xs text-gray-500 mb-2">Describe your notable achievements, awards, or recognitions</p>
                            <textarea name="achievements" id="achievements" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>{{ old('achievements', $alumni->achievements) }}</textarea>
                            @error('achievements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="certifications" class="block text-sm font-medium text-gray-700 mb-1">Certifications</label>
                            <p class="text-xs text-gray-500 mb-2">List your professional certifications or licenses</p>
                            <textarea name="certifications" id="certifications" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md{{ isset($readonly) && $readonly ? ' bg-gray-100' : '' }}" {{ isset($readonly) && $readonly ? 'readonly' : '' }}>{{ old('certifications', $alumni->certifications) }}</textarea>
                            @error('certifications')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    @if(!(isset($readonly) && $readonly))
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    @else
                        <div class="flex justify-between">
                            <a href="{{ route('support.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-question-circle mr-2"></i> Contact Support
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
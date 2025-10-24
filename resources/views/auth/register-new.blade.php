<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BukSU AlumniConnect - Create Account</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-primary flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                    </svg>
                </div>
            </div>
            
            <h2 class="text-3xl font-display font-bold text-buksu-navy-900">Create Your Account</h2>
            <p class="mt-2 text-gray-600">Join the BukSU AlumniConnect community today</p>
        </div>

        <!-- Register Card -->
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-buksu-navy-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name <span class="text-error-500">*</span>
                                </label>
                                <input 
                                    id="first_name" 
                                    class="input @error('first_name') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                    type="text" 
                                    name="first_name" 
                                    value="{{ old('first_name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="given-name"
                                    placeholder="Juan"
                                >
                                @error('first_name')
                                    <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name <span class="text-error-500">*</span>
                                </label>
                                <input 
                                    id="last_name" 
                                    class="input @error('last_name') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                    type="text" 
                                    name="last_name" 
                                    value="{{ old('last_name') }}" 
                                    required 
                                    autocomplete="family-name"
                                    placeholder="Dela Cruz"
                                >
                                @error('last_name')
                                    <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-buksu-navy-900 mb-4">Academic Information</h3>
                        
                        <div class="space-y-4">
                            <!-- Student ID -->
                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Student ID Number <span class="text-error-500">*</span>
                                </label>
                                <input 
                                    id="student_id" 
                                    class="input @error('student_id') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                    type="text" 
                                    name="student_id" 
                                    value="{{ old('student_id') }}" 
                                    required 
                                    placeholder="2020-12345"
                                >
                                @error('student_id')
                                    <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Batch Year -->
                                <div>
                                    <label for="batch_year" class="block text-sm font-medium text-gray-700 mb-2">
                                        Batch Year <span class="text-error-500">*</span>
                                    </label>
                                    <input 
                                        id="batch_year" 
                                        class="input @error('batch_year') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                        type="number" 
                                        name="batch_year" 
                                        value="{{ old('batch_year') }}" 
                                        required 
                                        min="1950" 
                                        max="{{ date('Y') + 10 }}"
                                        placeholder="2024"
                                    >
                                    @error('batch_year')
                                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Department -->
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                        Department <span class="text-error-500">*</span>
                                    </label>
                                    <input 
                                        id="department" 
                                        class="input @error('department') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                        type="text" 
                                        name="department" 
                                        value="{{ old('department') }}" 
                                        required 
                                        placeholder="College of Engineering"
                                    >
                                    @error('department')
                                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Degree -->
                            <div>
                                <label for="degree" class="block text-sm font-medium text-gray-700 mb-2">
                                    Degree Program <span class="text-error-500">*</span>
                                </label>
                                <input 
                                    id="degree" 
                                    class="input @error('degree') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                    type="text" 
                                    name="degree" 
                                    value="{{ old('degree') }}" 
                                    required 
                                    placeholder="Bachelor of Science in Computer Science"
                                >
                                @error('degree')
                                    <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Security Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-buksu-navy-900 mb-4">Account Security</h3>
                        
                        <div class="space-y-4">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-error-500">*</span>
                                </label>
                                <input 
                                    id="email" 
                                    class="input @error('email') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-error-500">*</span>
                                    </label>
                                    <input 
                                        id="password" 
                                        class="input @error('password') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Min. 8 characters"
                                    >
                                    @error('password')
                                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirm Password <span class="text-error-500">*</span>
                                    </label>
                                    <input 
                                        id="password_confirmation" 
                                        class="input" 
                                        type="password" 
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Re-enter password"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms" required class="mt-1 w-4 h-4 text-buksu-navy-600 bg-gray-100 border-gray-300 rounded focus:ring-buksu-navy-500 focus:ring-2">
                            <span class="ml-3 text-sm text-gray-700">
                                I agree to the <a href="#" class="text-buksu-navy-700 hover:text-buksu-navy-900 font-medium">Terms & Conditions</a> and <a href="#" class="text-buksu-navy-700 hover:text-buksu-navy-900 font-medium">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create Alumni Account
                    </button>
                </form>

                <!-- Google Registration -->
                @if(Route::has('auth.google'))
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or sign up with</span>
                        </div>
                    </div>

                    <a href="{{ route('auth.google') }}" class="btn btn-outline w-full">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#EA4335" d="M5.27 9.76A7.5 7.5 0 1119.5 12c0-.69-.06-1.36-.18-2H12v4.5h4.31a3.75 3.75 0 01-6.31 2.26l-.01.01-3-2.33-.09.07A7.5 7.5 0 015.27 9.76z"/>
                            <path fill="#4285F4" d="M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            <path fill="#34A853" d="M5.27 14.24A7.48 7.48 0 0012 19.5c1.93 0 3.68-.63 5.11-1.68l-2.99-2.33A4.5 4.5 0 015.27 14.24z"/>
                            <path fill="#FBBC05" d="M5.27 9.76L2.26 7.45A7.5 7.5 0 015.27 9.76z"/>
                        </svg>
                        Continue with Google
                    </a>
                @endif
            </div>
        </div>

        <!-- Login Link -->
        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-buksu-navy-700 hover:text-buksu-navy-900">
                Sign in here
            </a>
        </p>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ route('central.landing') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-buksu-navy-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to home
            </a>
        </div>
    </div>
</body>
</html>

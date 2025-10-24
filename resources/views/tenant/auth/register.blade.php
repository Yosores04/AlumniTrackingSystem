<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BukSU AlumniConnect</title>
    <link rel="icon" type="image/png" href="{{ asset('img/buksu-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-buksu-navy-900 via-buksu-navy-800 to-buksu-navy-900 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-96 h-96 bg-buksu-gold-500 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-buksu-gold-500 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 py-16 text-white">
                @php
                    $settings = null;
                    
                    if (function_exists('tenant') && tenant()) {
                        try {
                            $settings = \App\Models\TenantSettings::getSettings();
                        } catch (\Exception $e) {
                            // Fallback to null
                        }
                    }
                    
                    if (!$settings) {
                        $settings = new \stdClass();
                        $settings->logo_path = null;
                        $settings->logo_url = null;
                    }
                @endphp
                
                @if(isset($settings->logo_path) && $settings->logo_path)
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-20 mb-6">
                @elseif(isset($settings->logo_url) && $settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="Logo" class="h-20 mb-6">
                @else
                    <div class="w-16 h-16 mb-6 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                @endif
                
                <h1 class="text-4xl font-display font-bold mb-3 text-center text-white">Join BukSU AlumniConnect</h1>
                <p class="text-lg text-yellow-300 text-center max-w-md mb-10">Become part of our thriving alumni community and unlock new opportunities.</p>
                
                <!-- Benefits -->
                <div class="space-y-5 max-w-md w-full">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Connect with Alumni</h3>
                            <p class="text-sm text-white/70">Build your professional network</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Career Opportunities</h3>
                            <p class="text-sm text-white/70">Access exclusive job postings</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Stay Updated</h3>
                            <p class="text-sm text-white/70">University news and events</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    @if(isset($settings->logo_path) && $settings->logo_path)
                        <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-16 mx-auto mb-4">
                    @elseif(isset($settings->logo_url) && $settings->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="Logo" class="h-16 mx-auto mb-4">
                    @else
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-buksu-navy-600 to-buksu-navy-800 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-display font-bold text-buksu-navy-900 mb-2">Create Your Account</h2>
                    <p class="text-buksu-navy-600">Join the BukSU alumni community today</p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-buksu-navy-700 mb-2">
                            Full Name
                        </label>
                        <input 
                            id="name" 
                            class="input w-full" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter your full name"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-buksu-navy-700 mb-2">
                            Email Address
                        </label>
                        <input 
                            id="email" 
                            class="input w-full" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-buksu-navy-700 mb-2">
                            Password
                        </label>
                        <input 
                            id="password" 
                            class="input w-full"
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="Create a strong password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-buksu-navy-700 mb-2">
                            Confirm Password
                        </label>
                        <input 
                            id="password_confirmation" 
                            class="input w-full"
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                        >
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Create Alumni Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-buksu-navy-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-semibold text-buksu-navy-700 hover:text-buksu-navy-900 transition">
                            Sign In
                        </a>
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-6 text-center">
                    <a href="{{ route('central.landing') }}" class="inline-flex items-center text-sm text-buksu-navy-600 hover:text-buksu-navy-900 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @include('components.toast-notification')
</body>
</html>

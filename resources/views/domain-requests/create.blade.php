<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Request a Domain - BukSU AlumniConnect</title>
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
                <div class="w-20 h-20 mb-6 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-display font-bold mb-3 text-center text-white">Request Your Campus Domain</h1>
                <p class="text-lg text-yellow-300 text-center max-w-md mb-10">Create a dedicated portal for your campus or department alumni community.</p>
                
                <!-- Benefits -->
                <div class="space-y-5 max-w-md w-full">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Custom Subdomain</h3>
                            <p class="text-sm text-white/70">Get your own branded portal URL</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Full Admin Access</h3>
                            <p class="text-sm text-white/70">Manage your alumni database independently</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Quick Approval</h3>
                            <p class="text-sm text-white/70">Get started within 24-48 hours</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Email Notifications</h3>
                            <p class="text-sm text-white/70">Receive login credentials via email</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Request Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-buksu-navy-600 to-buksu-navy-800 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-display font-bold text-buksu-navy-900 mb-2">Request a <span class="text-gradient">Domain</span></h2>
                    <p class="text-buksu-navy-600">Fill out this form to request your customized campus portal</p>
                </div>

                <!-- Request Form -->
                <form method="POST" action="{{ route('domain-requests.store') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="admin_name" class="block text-sm font-medium text-buksu-navy-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="admin_name" 
                            id="admin_name" 
                            class="input w-full @error('admin_name') border-red-500 @enderror" 
                            value="{{ old('admin_name') }}" 
                            required
                            placeholder="Juan Dela Cruz"
                        >
                        @error('admin_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-buksu-navy-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="admin_email" 
                            id="admin_email" 
                            class="input w-full @error('admin_email') border-red-500 @enderror" 
                            value="{{ old('admin_email') }}" 
                            required
                            placeholder="admin@example.com"
                        >
                        <p class="mt-1 text-xs text-buksu-navy-600">This email will be used for login credentials and communication.</p>
                        @error('admin_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Domain Prefix -->
                    <div>
                        <label for="domain_prefix" class="block text-sm font-medium text-buksu-navy-700 mb-2">
                            Desired Domain Prefix <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-stretch rounded-lg overflow-hidden border border-buksu-navy-300 focus-within:ring-2 focus-within:ring-buksu-navy-500 focus-within:border-transparent">
                            <input 
                                type="text" 
                                name="domain_prefix" 
                                id="domain_prefix" 
                                class="flex-1 px-4 py-2.5 border-0 focus:ring-0 text-buksu-navy-900 placeholder-buksu-navy-400 @error('domain_prefix') border-red-500 @enderror" 
                                value="{{ old('domain_prefix') }}" 
                                required 
                                placeholder="myschool"
                            >
                            <span class="inline-flex items-center px-4 bg-buksu-navy-50 text-buksu-navy-700 font-medium text-sm border-l border-buksu-navy-300">
                                .localhost:8000
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-buksu-navy-600">Only letters, numbers, and hyphens are allowed.</p>
                        @error('domain_prefix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Submit Request
                    </button>
                </form>

                <!-- What Happens Next -->
                <div class="mt-8 p-5 bg-buksu-navy-50 border border-buksu-navy-100 rounded-xl">
                    <h3 class="font-display font-semibold text-buksu-navy-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-buksu-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        What happens next?
                    </h3>
                    <ul class="space-y-2 text-sm text-buksu-navy-700">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-buksu-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Your request will be reviewed by our administrators</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-buksu-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Once approved, we'll create your custom domain</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-buksu-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>You'll receive an email with login credentials</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-buksu-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Change your temporary password within 24 hours</span>
                        </li>
                    </ul>
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

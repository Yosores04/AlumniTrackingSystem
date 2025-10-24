<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login - BukSU AlumniConnect</title>
    <link rel="icon" type="image/png" href="{{ asset('img/buksu-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-buksu-navy-900 via-buksu-navy-800 to-buksu-navy-900 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-buksu-gold-500 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-buksu-gold-500 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 py-16 text-white">
                <div class="w-20 h-20 mb-6 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-display font-bold mb-3 text-center text-white">Administrator Portal</h1>
                <p class="text-lg text-yellow-300 text-center max-w-md mb-10">Secure access to BukSU AlumniConnect central management system.</p>
                
                <!-- Feature Highlights -->
                <div class="space-y-5 max-w-md w-full">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">System Management</h3>
                            <p class="text-sm text-white/70">Manage all campus tenants and settings</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">User Administration</h3>
                            <p class="text-sm text-white/70">Control access and permissions</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Reports & Analytics</h3>
                            <p class="text-sm text-white/70">University-wide data insights</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-buksu-navy-600 to-buksu-navy-800 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-display font-bold text-buksu-navy-900 mb-2">Administrator Sign In</h2>
                    <p class="text-buksu-navy-600">Access the central management system</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('central.login') }}" class="space-y-5">
                    @csrf

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
                            autofocus 
                            autocomplete="username"
                            placeholder="admin@buksu.edu.ph"
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
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-buksu-navy-300 text-buksu-navy-600 focus:ring-buksu-navy-500">
                            <span class="ml-2 text-sm text-buksu-navy-600">Remember me</span>
                        </label>

                        @if (Route::has('central.password.request'))
                            <a href="{{ route('central.password.request') }}" class="text-sm text-buksu-navy-600 hover:text-buksu-navy-900 transition">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In as Administrator
                    </button>
                </form>

                <!-- Security Notice -->
                <div class="mt-8 p-4 bg-buksu-gold-50 border border-buksu-gold-200 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-buksu-gold-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-buksu-gold-900 mb-1">Secure Access</p>
                            <p class="text-xs text-buksu-gold-800">This portal is for authorized administrators only. All access is logged and monitored.</p>
                        </div>
                    </div>
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

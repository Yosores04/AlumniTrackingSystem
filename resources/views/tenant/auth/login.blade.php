<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Login - BukSU AlumniConnect</title>
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
                
                <h1 class="text-4xl font-display font-bold mb-3 text-center text-white">BukSU AlumniConnect</h1>
                <p class="text-lg text-yellow-300 text-center max-w-md mb-10">Connecting graduates, building futures, strengthening our community.</p>
                
                <!-- Feature Highlights -->
                <div class="space-y-5 max-w-md w-full">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Track Your Journey</h3>
                            <p class="text-sm text-white/70">Stay connected with your alma mater</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Network & Grow</h3>
                            <p class="text-sm text-white/70">Connect with fellow alumni worldwide</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-base mb-1">Give Back</h3>
                            <p class="text-sm text-white/70">Support and mentor the next generation</p>
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
                    <h2 class="text-3xl font-display font-bold text-buksu-navy-900 mb-2">Welcome Back</h2>
                    <p class="text-buksu-navy-600">Sign in to your alumni account</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
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

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-buksu-navy-600 hover:text-buksu-navy-900 transition">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In
                    </button>
                </form>

                <!-- Google Login -->
                @if(Route::has('auth.google'))
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-buksu-navy-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-buksu-navy-500">Or continue with</span>
                        </div>
                    </div>

                    <a href="{{ route('auth.google') }}" class="btn btn-secondary w-full">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Sign in with Google
                    </a>
                @endif

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-buksu-navy-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-semibold text-buksu-navy-700 hover:text-buksu-navy-900 transition">
                            Create Alumni Account
                        </a>
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-6 text-center">
                    <a href="/" class="inline-flex items-center text-sm text-buksu-navy-600 hover:text-buksu-navy-900 transition">
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

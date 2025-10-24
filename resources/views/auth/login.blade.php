<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BukSU AlumniConnect - Sign In</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            @php
                $settings = null;
                
                if (function_exists('tenant') && tenant()) {
                    try {
                        $settings = \App\Models\TenantSettings::getSettings();
                    } catch (\Exception $e) {
                        // Fallback to null if settings can't be retrieved
                    }
                }
                
                if (!$settings) {
                    $settings = new \stdClass();
                    $settings->logo_path = null;
                    $settings->logo_url = null;
                }
            @endphp
            
            <div class="flex justify-center mb-6">
                @if(isset($settings->logo_path) && $settings->logo_path)
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-16 w-auto">
                @elseif(isset($settings->logo_url) && $settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="Logo" class="h-16 w-auto">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-gradient-primary flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            
            <h2 class="text-3xl font-display font-bold text-buksu-navy-900">Welcome Back</h2>
            <p class="mt-2 text-gray-600">Sign in to your alumni account</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success">
                <div class="flex">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <!-- Login Card -->
        <div class="card">
            <div class="card-body">
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input 
                            id="email" 
                            class="input @error('email') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            id="password" 
                            class="input @error('password') border-error-500 focus:border-error-500 focus:ring-error-100 @enderror"
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-buksu-navy-600 bg-gray-100 border-gray-300 rounded focus:ring-buksu-navy-500 focus:ring-2">
                            <span class="ml-2 text-sm text-gray-700">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-buksu-navy-700 hover:text-buksu-navy-900">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Sign In
                    </button>
                </form>

                <!-- Google Login -->
                @if(Route::has('auth.google'))
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <a href="{{ route('auth.google') }}" class="btn btn-outline w-full">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#EA4335" d="M5.27 9.76A7.5 7.5 0 1119.5 12c0-.69-.06-1.36-.18-2H12v4.5h4.31a3.75 3.75 0 01-6.31 2.26l-.01.01-3-2.33-.09.07A7.5 7.5 0 015.27 9.76z"/>
                            <path fill="#4285F4" d="M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            <path fill="#34A853" d="M5.27 14.24A7.48 7.48 0 0112 19.5c1.93 0 3.68-.63 5.11-1.68l-2.99-2.33A4.5 4.5 0 015.27 14.24z"/>
                            <path fill="#FBBC05" d="M5.27 9.76L2.26 7.45A7.5 7.5 0 015.27 9.76z"/>
                        </svg>
                        Sign in with Google
                    </a>
                @endif
            </div>
        </div>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-buksu-navy-700 hover:text-buksu-navy-900">
                Create an alumni account
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
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .remember-me label {
            font-size: 14px;
            color: #475569;
            font-weight: 400;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1e3a8a, #0f172a);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3);
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(30, 58, 138, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin: 20px 0;
        }

        .forgot-password a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .forgot-password a:hover {
            color: #dc2626;
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(30, 58, 138, 0.1);
        }

        .register-link p {
            color: #475569;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .register-button {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: #1e3a8a;
            border: 2px solid rgba(30, 58, 138, 0.2);
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .register-button:hover {
            background: rgba(30, 58, 138, 0.05);
            transform: translateY(-1px);
        }

        .google-login-button {
            width: 100%;
            padding: 14px;
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 16px;
        }

        .google-login-button:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .google-icon {
            width: 18px;
            height: 18px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: #6b7280;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .divider::before {
            margin-right: 16px;
        }

        .divider::after {
            margin-left: 16px;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            font-weight: 500;
            margin-top: 4px;
        }

        .success-message {
            color: #059669;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 16px;
            padding: 12px;
            background: rgba(5, 150, 105, 0.1);
            border-radius: 8px;
            border-left: 4px solid #059669;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
                margin: 16px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            @php
                $settings = null;
                
                // Only try to get tenant settings if we're in a tenant context
                if (function_exists('tenant') && tenant()) {
                    try {
                        $settings = \App\Models\TenantSettings::getSettings();
                    } catch (\Exception $e) {
                        // Fallback to null if settings can't be retrieved
                    }
                }
                
                // If no settings were found (central domain or new tenant), create a default object
                if (!$settings) {
                    $settings = new \stdClass();
                    $settings->logo_path = null;
                    $settings->logo_url = null;
                }
            @endphp
            
            @if(isset($settings->logo_path) && $settings->logo_path)
                <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="auth-logo">
            @elseif(isset($settings->logo_url) && $settings->logo_url)
                <img src="{{ $settings->logo_url }}" alt="Logo" class="auth-logo">
            @endif
            
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your alumni account to continue</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input 
                    id="email" 
                    class="form-input" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="Enter your email address"
                >
                @if($errors->get('email'))
                    <div class="error-message">{{ $errors->get('email')[0] }}</div>
                @endif
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input 
                    id="password" 
                    class="form-input"
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
                @if($errors->get('password'))
                    <div class="error-message">{{ $errors->get('password')[0] }}</div>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-button">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <!-- Google Login -->
        @if(Route::has('auth.google'))
            <div class="divider">or</div>
            <a href="{{ route('auth.google') }}" class="google-login-button">
                <svg class="google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                <span>Sign in with Google</span>
            </a>
        @endif

        <!-- Forgot Password -->
        @if (Route::has('password.request'))
            <div class="forgot-password">
                <a href="{{ route('password.request') }}">
                    <i class="fas fa-key"></i> Forgot your password?
                </a>
            </div>
        @endif

        <!-- Register Link -->
        <div class="register-link">
            <p>Don't have an account?</p>
            <a href="{{ route('register') }}" class="register-button">
                <i class="fas fa-user-plus"></i> Create Alumni Account
            </a>
        </div>
    </div>
</body>
</html>
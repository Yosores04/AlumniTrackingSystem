<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Login</title>

    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Embed styles directly to ensure they work on all domains -->
    <style>
        /* Base styles */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative;
            background: transparent;
        }

        /* Logo styling */
        .logo {
            color: white;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 15px;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        .nstp_logo {
            height: 60px;
            width: auto;
            margin-right: 15px;
        }

        .header-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header-text h6 {
            color: white;
            margin: 0;
            font-weight: bold;
            font-size: 1.2rem;
            line-height: 1.2;
        }

        /* Circle background */
        .circle {
            position: fixed;
            left: 0;
            top: 0;
            width: 100vh;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
            z-index: 0;
        }

        .circle::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 200vh;
            height: 200vh;
            background-color: rgba(19, 50, 91, 0.9);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
        }

        /* Form styling */
        .login-form {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
            margin-left: -30%;
            background-color: transparent;
        }

        .card {
            background-color: transparent;
            border: none;
        }

        .card-body {
            padding: 2rem;
        }

        h1 {
            margin-left: 70px;
            color: white;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .form-container {
            margin-top: 1rem;
        }

        .form-floating {
            width: 100%;
            opacity: 1;
        }

        /* Input styling */
        .input-group-text {
            background-color: rgba(255, 255, 255, 0.9);
            border-right: none;
        }

        .input-group .form-control {
            background-color: rgba(255, 255, 255, 0.9);
            border-left: none;
        }

        .form-control {
            height: 45px;
        }

        /* Button styling */
        .btn {
            width: 100%;
            margin-top: 1rem;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            opacity: 0.9;
        }

        .btn-outline-dark {
            background-color: white;
            color: #444;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-outline-dark:hover {
            background-color: #f5f5f5;
            color: #333;
        }

        /* Checkbox styling */
        .checkbox-container {
            text-align: left;
            margin: 1rem 0;
        }

        .checkbox {
            color: white;
            margin-left: 0.5rem;
        }

        /* Google logo */
        .google_logo {
            width: 18px;
            height: 18px;
            display: inline-block;
            vertical-align: middle;
        }

        /* Background image */
        .nstp_bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
        }

        /* reCAPTCHA container */
        .recaptcha-container {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        /* Password toggle button */
        .password-toggle-btn {
            width: auto !important;
            margin-top: 0;
            background: white;
            border: 1px solid #ced4da;
            border-left: none;
            color: #666;
        }

        .password-toggle-btn:hover {
            background: #f8f9fa;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .circle {
                width: 100%;
            }
            
            .login-form {
                margin-left: 0;
                padding: 1rem;
            }
            
            .form-floating {
                width: 100%;
            }
        }

        /* Text colors for links */
        .text-indigo-600 {
            color: #f0f0f0 !important;
        }
        
        .text-green-600 {
            color: #f0f0f0 !important;
        }
    </style>
</head>
<body>
    <!-- Background image - using full URL to ensure it works on all domains -->
    <div class="nstp_bg" style="background-image: url('{{ isset($settings->background_image_path) && $settings->background_image_path ? Storage::url($settings->background_image_path) : (isset($settings->background_image_url) && $settings->background_image_url ? $settings->background_image_url : asset('img/default-background.jpg')) }}')"></div>

    <!-- Logo at top left -->
    <div class="logo">
        <img src="{{ isset($settings->logo_path) && $settings->logo_path ? Storage::url($settings->logo_path) : (isset($settings->logo_url) && $settings->logo_url ? $settings->logo_url : url('/img/1.svg')) }}" alt="Logo" class="nstp_logo">
        <div class="header-text">
            <h6>{{ isset($settings->site_name) ? $settings->site_name : 'Alumni Monitoring System' }}</h6>
        </div>
    </div>

    <!-- Background with circle -->
    <div class="circle">
        <!-- Main Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm" class="login-form">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h1>WELCOME</h1>
                    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="form-container">
                        <div class="form-floating">
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="Email address" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Password" required>
                                <button type="button" class="btn password-toggle-btn" onclick="togglePassword()">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <div class="checkbox-container">
                                <input type="checkbox" name="remember" id="checkbox">
                                <label class="checkbox" for="checkbox">
                                    Keep me logged in
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                </div>
                                <!-- Only show domain request link on central domain -->
                                @if(!isset($isTenant) || !$isTenant)
                                <div class="text-sm">
                                    <a href="{{ route('request-domain') }}" class="font-medium text-green-600 hover:text-green-500">
                                        {{ __('Request your own domain') }}
                                    </a>
                                </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Login</button>
                            
                            <!-- Google login -->
                            @if(Route::has('auth.google'))
                            <a href="{{ route('auth.google') }}" class="btn btn-outline-dark">
                                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                </svg>
                                <span>Sign in with Google</span>
                            </a>
                            @endif

                            <div class="recaptcha-container">
                                @if(class_exists('\Anhskohbo\NoCaptcha\Facades\NoCaptcha'))
                                    {!! NoCaptcha::display() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    @if(class_exists('\Anhskohbo\NoCaptcha\Facades\NoCaptcha'))
        {!! NoCaptcha::renderJs() !!}
    @endif
    <script>
        function togglePassword() {
            const passwordInput = document.querySelector('input[type="password"]');
            const toggleIcon = document.querySelector('.password-toggle-btn i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
</body>
</html>

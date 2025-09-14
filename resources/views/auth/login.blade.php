<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Login</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(30, 58, 138, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo {
            height: 50px;
            width: auto;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .login-subtitle {
            font-size: 16px;
            color: #475569;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid rgba(30, 58, 138, 0.1);
            border-radius: 12px;
            font-size: 16px;
            font-weight: 400;
            color: #1f2937;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #1e3a8a;
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-input::placeholder {
            color: #94a3b8;
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
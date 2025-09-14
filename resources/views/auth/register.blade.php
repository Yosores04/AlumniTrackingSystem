<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Register</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --brand-primary: #1e3a8a;
            --brand-secondary: #0f172a;
            --accent-color: #dc2626;
            --brand-primary-rgb: 30, 58, 138;
            --brand-secondary-rgb: 15, 23, 42;
            --accent-color-rgb: 220, 38, 38;
            
            /* University professional variables */
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(30, 58, 138, 0.1);
            --card-bg: rgba(255, 255, 255, 0.98);
            --text-muted: #475569;
            --university-gold: #d97706;
            --university-gray: #64748b;
            
            /* Typography System */
            --font-family-base: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            --font-weight-light: 300;
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-semibold: 600;
            --font-weight-bold: 700;
            --font-weight-extrabold: 800;
            --font-weight-black: 900;
            
            /* Font Sizes */
            --text-xs: 0.75rem;
            --text-sm: 0.875rem;
            --text-base: 1rem;
            --text-lg: 1.125rem;
            --text-xl: 1.25rem;
            --text-2xl: 1.5rem;
            --text-3xl: 1.875rem;
            --text-4xl: 2.25rem;
            
            /* Line Heights */
            --leading-tight: 1.25;
            --leading-snug: 1.375;
            --leading-normal: 1.5;
            --leading-relaxed: 1.625;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family-base);
            font-weight: var(--font-weight-normal);
            line-height: var(--leading-normal);
            color: #1f2937;
            background: linear-gradient(135deg, 
                rgba(var(--brand-primary-rgb), 0.9) 0%,
                rgba(var(--brand-secondary-rgb), 0.8) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Auth Container */
        .auth-container {
            width: 100%;
            max-width: 500px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        /* Header */
        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-logo {
            height: 50px;
            width: auto;
            margin-bottom: 1.5rem;
            border-radius: 8px;
        }

        .auth-title {
            font-size: var(--text-3xl);
            font-weight: var(--font-weight-bold);
            color: var(--brand-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
            line-height: var(--leading-tight);
        }

        .auth-subtitle {
            font-size: var(--text-base);
            color: var(--text-muted);
            font-weight: var(--font-weight-normal);
            line-height: var(--leading-relaxed);
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: var(--text-sm);
            font-weight: var(--font-weight-semibold);
            color: var(--brand-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.01em;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid rgba(var(--brand-primary-rgb), 0.1);
            border-radius: 12px;
            font-size: var(--text-base);
            font-weight: var(--font-weight-normal);
            color: #1f2937;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            font-family: var(--font-family-base);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.1);
        }

        /* Button Styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: var(--font-weight-semibold);
            font-size: var(--text-base);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid;
            cursor: pointer;
            letter-spacing: -0.01em;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            color: white;
            border-color: transparent;
            box-shadow: 0 8px 20px rgba(var(--brand-primary-rgb), 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(var(--brand-primary-rgb), 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--brand-primary);
            border-color: rgba(var(--brand-primary-rgb), 0.2);
        }

        .btn-secondary:hover {
            background: rgba(var(--brand-primary-rgb), 0.05);
            transform: translateY(-1px);
        }

        /* Links */
        .auth-link {
            color: var(--brand-primary);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            font-size: var(--text-sm);
            transition: color 0.3s ease;
        }

        .auth-link:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* Footer Links */
        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(var(--brand-primary-rgb), 0.1);
        }

        .auth-footer p {
            font-size: var(--text-sm);
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        /* Error Messages */
        .error-message {
            color: var(--accent-color);
            font-size: var(--text-xs);
            font-weight: var(--font-weight-medium);
            margin-top: 0.25rem;
        }

        /* Grid Layout for Name Fields */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .auth-title {
                font-size: var(--text-2xl);
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            @php
                $settings = null;
                
                // Only try to get tenant settings if we're in a tenant context
                if (function_exists('tenant') && tenant()) {
                    try {
                        $settings = \App\Models\TenantSettings::getSettings();
                    } catch (\Exception $e) {
                        // Fallback to null if settings can't be retrieved
                        // This could happen if the tenant database isn't set up yet
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
            
            <h1 class="auth-title">Join Our Alumni Network</h1>
            <p class="auth-subtitle">Create your account to connect with fellow graduates</p>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-user mr-2"></i>Full Name
                </label>
                <input 
                    id="name" 
                    class="form-control" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name"
                    placeholder="Enter your full name"
                >
                <x-input-error :messages="$errors->get('name')" class="error-message" />
            </div>

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope mr-2"></i>Email Address
                </label>
                <input 
                    id="email" 
                    class="form-control" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="username"
                    placeholder="Enter your email address"
                >
                <x-input-error :messages="$errors->get('email')" class="error-message" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <input 
                    id="password" 
                    class="form-control"
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    placeholder="Create a strong password"
                >
                <x-input-error :messages="$errors->get('password')" class="error-message" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-lock mr-2"></i>Confirm Password
                </label>
                <input 
                    id="password_confirmation" 
                    class="form-control"
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                >
                <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Alumni Account
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="auth-footer">
            <p>Already have an account?</p>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In to Your Account
            </a>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Login</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Background image -->
    <div class="nstp_bg"></div>
    <script>
        document.querySelector('.nstp_bg').style.backgroundImage = 'url("{{ asset('img/Login_Background.jpg') }}")';
    </script>

    <div class="page-container">
        <!-- Circle with Login Content -->
        <div class="circle">
            <div class="login-content">
                <!-- Logo and Title -->
                <div class="logo-container mb-4">
                    <div class="logo-wrapper">
                        <img src="{{ asset('images/NSTP_LOGO.png') }}" alt="Logo" class="nstp_logo">
                        <div class="header-text">
                            <h1 class="system-title">ALUMNI Monitoring System</h1>
                        </div>
                    </div>
                </div>

                <!-- Login Card -->
                <div class="login-card-container">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3 class="welcome-text">Welcome</h3>
                            </div>
                            
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            
                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}" id="loginForm">
                                @csrf
                                <div class="form-container">
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

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="remember" id="checkbox" class="form-check-input">
                                            <label class="form-check-label" for="checkbox">
                                                Keep me logged in
                                            </label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                                            Forgot password?
                                        </a>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mb-3">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                    </button>

                                    <a href="{{ route('auth.google') }}" class="btn btn-light w-100 mb-3">
                                        <img src="{{ asset('images/google.svg') }}" alt="Google" class="google_logo me-2">
                                        Sign in with Google
                                    </a>

                                    <div class="text-center">
                                        <a href="{{ route('request-domain') }}" class="text-decoration-none">
                                            Request your own domain
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

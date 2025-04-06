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
    <!-- Background image - moved to top -->
    <div class="nstp_bg" style="background-image: url('{{ asset('../img/Login_Background.jpg') }}')"></div>

    <!-- Logo at top left -->
    <div class="logo">
        <img src="{{ asset('images/NSTP_LOGO.png') }}" alt="Logo" class="nstp_logo">
        <div class="header-text">
            <h6>ALUMNI </h6>
            <h6>Monitoring System</h6>
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

                            <button type="submit" class="btn btn-primary">Login</button>
                            
                            <a href="{{ route('auth.google') }}" class="btn btn-outline-dark">
                                <img src="{{ asset('images/google.svg') }}" alt="Google" class="google_logo">
                                Sign in with Google
                            </a>

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

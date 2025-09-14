<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings->site_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: {{ $settings->primary_color ?? '#1e3a8a' }};
            --brand-secondary: {{ $settings->secondary_color ?? '#0f172a' }};
            --accent-color: {{ $settings->accent_color ?? '#dc2626' }};
            --brand-primary-rgb: {{ implode(', ', sscanf($settings->primary_color ?? '#1e3a8a', "#%02x%02x%02x")) }};
            --brand-secondary-rgb: {{ implode(', ', sscanf($settings->secondary_color ?? '#0f172a', "#%02x%02x%02x")) }};
            --accent-color-rgb: {{ implode(', ', sscanf($settings->accent_color ?? '#dc2626', "#%02x%02x%02x")) }};
            
            /* University professional variables */
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(30, 58, 138, 0.1);
            --card-bg: rgba(255, 255, 255, 0.98);
            --text-muted: #475569;
            --header-text: #FFFFFF;
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
            --text-xs: 0.75rem;      /* 12px */
            --text-sm: 0.875rem;     /* 14px */
            --text-base: 1rem;       /* 16px */
            --text-lg: 1.125rem;     /* 18px */
            --text-xl: 1.25rem;      /* 20px */
            --text-2xl: 1.5rem;      /* 24px */
            --text-3xl: 1.875rem;    /* 30px */
            --text-4xl: 2.25rem;     /* 36px */
            --text-5xl: 3rem;        /* 48px */
            --text-6xl: 3.75rem;     /* 60px */
            --text-7xl: 4.5rem;      /* 72px */
            
            /* Line Heights */
            --leading-tight: 1.25;
            --leading-snug: 1.375;
            --leading-normal: 1.5;
            --leading-relaxed: 1.625;
            --leading-loose: 2;
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
            background: #f8fafc;
            font-size: var(--text-base);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Typography Classes */
        .text-display-1 {
            font-size: var(--text-7xl);
            font-weight: var(--font-weight-black);
            line-height: var(--leading-tight);
            letter-spacing: -0.05em;
        }
        
        .text-display-2 {
            font-size: var(--text-6xl);
            font-weight: var(--font-weight-extrabold);
            line-height: var(--leading-tight);
            letter-spacing: -0.04em;
        }
        
        .text-display-3 {
            font-size: var(--text-5xl);
            font-weight: var(--font-weight-bold);
            line-height: var(--leading-tight);
            letter-spacing: -0.03em;
        }
        
        .text-heading-1 {
            font-size: var(--text-4xl);
            font-weight: var(--font-weight-bold);
            line-height: var(--leading-tight);
            letter-spacing: -0.025em;
        }
        
        .text-heading-2 {
            font-size: var(--text-3xl);
            font-weight: var(--font-weight-bold);
            line-height: var(--leading-tight);
            letter-spacing: -0.02em;
        }
        
        .text-heading-3 {
            font-size: var(--text-2xl);
            font-weight: var(--font-weight-semibold);
            line-height: var(--leading-snug);
            letter-spacing: -0.015em;
        }
        
        .text-body-lg {
            font-size: var(--text-lg);
            font-weight: var(--font-weight-normal);
            line-height: var(--leading-relaxed);
        }
        
        .text-body {
            font-size: var(--text-base);
            font-weight: var(--font-weight-normal);
            line-height: var(--leading-normal);
        }
        
        .text-body-sm {
            font-size: var(--text-sm);
            font-weight: var(--font-weight-normal);
            line-height: var(--leading-normal);
        }

        /* Modern University Header */
        .main-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .brand-logo {
            height: 45px;
            width: auto;
            border-radius: 4px;
        }

        .brand-name {
            font-size: var(--text-2xl);
            font-weight: var(--font-weight-semibold);
            color: var(--brand-primary);
            letter-spacing: -0.02em;
        }

        .nav-link {
            color: var(--brand-primary);
            text-decoration: none;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-weight: var(--font-weight-medium);
            font-size: var(--text-sm);
            transition: all 0.3s ease;
            border: 1px solid transparent;
            letter-spacing: -0.01em;
        }

        .nav-link:hover {
            background: var(--brand-primary);
            color: white;
            transform: translateY(-1px);
        }

        /* University Hero Section */
        .hero-section {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(var(--brand-primary-rgb), 0.85) 0%,
                rgba(var(--brand-secondary-rgb), 0.75) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 900px;
            padding: 2rem;
        }

        .hero-title {
            font-size: clamp(var(--text-4xl), 5vw, var(--text-6xl));
            font-weight: var(--font-weight-extrabold);
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            line-height: var(--leading-tight);
            letter-spacing: -0.04em;
        }

        .hero-subtitle {
            font-size: clamp(var(--text-lg), 2vw, var(--text-xl));
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2rem;
            font-weight: var(--font-weight-normal);
            line-height: var(--leading-relaxed);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .welcome-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 600px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .welcome-text {
            color: var(--brand-primary);
            font-size: var(--text-lg);
            line-height: var(--leading-relaxed);
            font-weight: var(--font-weight-medium);
        }

        /* Buttons */
        .cta-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: var(--font-weight-semibold);
            font-size: var(--text-base);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid;
            cursor: pointer;
            backdrop-filter: blur(10px);
            letter-spacing: -0.01em;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            color: white;
            border-color: transparent;
            box-shadow: 0 8px 20px rgba(var(--brand-primary-rgb), 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(var(--brand-primary-rgb), 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-color: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .btn-lg {
            padding: 1.25rem 2.5rem;
            font-size: var(--text-lg);
            font-weight: var(--font-weight-semibold);
        }

        /* Features Section */
        .features-section {
            background: linear-gradient(135deg, 
                rgba(var(--brand-primary-rgb), 0.05) 0%,
                rgba(var(--brand-secondary-rgb), 0.02) 100%);
            backdrop-filter: blur(10px);
        }

        .feature-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .feature-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brand-primary), var(--accent-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-card:hover:before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--brand-primary), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 20px rgba(var(--brand-primary-rgb), 0.3);
        }

        .feature-title {
            font-size: var(--text-xl);
            font-weight: var(--font-weight-semibold);
            color: var(--brand-primary);
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .feature-description {
            color: var(--text-muted);
            line-height: var(--leading-relaxed);
            font-size: var(--text-base);
            font-weight: var(--font-weight-normal);
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, 
                rgba(var(--brand-secondary-rgb), 0.9) 0%,
                rgba(var(--brand-primary-rgb), 0.8) 100%);
            color: white;
        }

        .stat-card {
            text-align: center;
            padding: 2rem 1rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-label {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        /* CTA Section - University Style */
        .cta-section {
            background: linear-gradient(135deg,
                rgba(var(--brand-primary-rgb), 0.05) 0%,
                rgba(var(--university-gray), 0.02) 100%);
            backdrop-filter: blur(20px);
        }

        .cta-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 3rem 2.5rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .cta-title {
            font-size: var(--text-4xl);
            font-weight: var(--font-weight-bold);
            color: var(--brand-primary);
            margin-bottom: 1rem;
            letter-spacing: -0.025em;
            line-height: var(--leading-tight);
        }

        .cta-description {
            font-size: var(--text-lg);
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: var(--leading-relaxed);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-weight: var(--font-weight-normal);
        }

        /* Professional University Footer */
        .main-footer {
            background: linear-gradient(135deg,
                rgba(var(--brand-secondary-rgb), 0.95) 0%,
                rgba(var(--brand-primary-rgb), 0.9) 100%);
            color: white;
            position: relative;
        }

        .main-footer:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.25rem;
        }

        .social-link:hover {
            transform: translateY(-3px) scale(1.1);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease;
        }

        .animate-on-scroll.in-view {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content {
                padding: 1rem;
            }
            
            .welcome-card {
                margin: 1rem 0;
                padding: 1.5rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }
            
            .feature-card {
                margin-bottom: 2rem;
            }
            
            .main-header {
                padding: 1rem;
            }
            
            .nav-link {
                font-size: var(--text-xs);
                padding: 0.5rem 0.75rem;
            }
            
            /* Mobile Typography Adjustments */
            .text-display-3 {
                font-size: var(--text-4xl);
            }
            
            .text-heading-1 {
                font-size: var(--text-3xl);
            }
            
            .text-heading-2 {
                font-size: var(--text-2xl);
            }
            
            .text-heading-3 {
                font-size: var(--text-xl);
            }
            
            .hero-title {
                font-size: clamp(var(--text-3xl), 8vw, var(--text-5xl));
            }
            
            .hero-subtitle {
                font-size: var(--text-base);
            }
            
            .cta-title {
                font-size: var(--text-3xl);
            }
            
            .cta-description {
                font-size: var(--text-base);
            }
        }
        
        @media (max-width: 480px) {
            .hero-title {
                font-size: var(--text-3xl);
            }
            
            .text-display-3 {
                font-size: var(--text-3xl);
            }
            
            .cta-title {
                font-size: var(--text-2xl);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if($settings->logo_path)
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="{{ $settings->site_name }}" class="brand-logo">
                @elseif($settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_name }}" class="brand-logo">
                @endif
                <h1 class="brand-name">{{ $settings->site_name }}</h1>
            </div>
            
            <nav class="hidden md:flex space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link">
                        <i class="fas fa-chart-pie mr-2"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="fas fa-sign-in-alt mr-2"></i>Log In
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link">
                            <i class="fas fa-user-plus mr-2"></i>Join Us
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero-section" style="background-image: url('{{ $settings->background_image_path ? Storage::url($settings->background_image_path) : ($settings->background_image_url ? $settings->background_image_url : asset('img/default-background.jpg')) }}')">
            <div class="hero-overlay"></div>
            
            <div class="hero-content animate-fade-in-up">
                <h1 class="hero-title">{{ $settings->site_name }}</h1>
                
                @if($settings->site_description)
                    <p class="hero-subtitle">{{ $settings->site_description }}</p>
                @endif
                
                @if($settings->welcome_message)
                    <div class="welcome-card animate-float">
                        <p class="welcome-text">{!! nl2br(e($settings->welcome_message)) !!}</p>
                    </div>
                @endif
                
                <div class="cta-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Alumni Sign In
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-secondary">
                                <i class="fas fa-user-plus mr-2"></i>
                                Join Alumni Network
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section py-20">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16 animate-on-scroll">
                    <h2 class="text-display-3 text-gray-900 mb-4">
                        Stay Connected with Your 
                        <span style="color: var(--brand-primary);">Alma Mater</span>
                    </h2>
                    <p class="text-body-lg text-gray-600 max-w-3xl mx-auto">
                        Our comprehensive alumni platform helps graduates maintain lifelong connections 
                        with the university community and fellow alumni worldwide.
                    </p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="feature-title">Alumni Directory</h3>
                        <p class="feature-description">
                            Connect with fellow graduates from your program and graduating class. 
                            Search by degree, year, location, or industry to find meaningful connections.
                        </p>
                    </div>
                    
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3 class="feature-title">Career Services</h3>
                        <p class="feature-description">
                            Access career resources, job postings, and networking opportunities. 
                            Update your professional information to inspire current students.
                        </p>
                    </div>
                    
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Mentorship Program</h3>
                        <p class="feature-description">
                            Participate as a mentor for current students or connect with experienced 
                            alumni who can guide your professional development.
                        </p>
                    </div>
                    
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <h3 class="feature-title">University Events</h3>
                        <p class="feature-description">
                            Stay informed about homecoming, reunions, networking events, 
                            and special university celebrations in your area.
                        </p>
                    </div>
                    
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="feature-title">Give Back</h3>
                        <p class="feature-description">
                            Support your alma mater through volunteering, guest speaking, 
                            scholarship contributions, and other meaningful engagement opportunities.
                        </p>
                    </div>
                    
                    <div class="feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h3 class="feature-title">University News</h3>
                        <p class="feature-description">
                            Receive updates on university achievements, research breakthroughs, 
                            faculty news, and other important developments from campus.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="cta-section py-20">
            <div class="container mx-auto px-6">
                <div class="cta-card animate-on-scroll">
                    <h2 class="cta-title">Join Your Alumni Community</h2>
                    <p class="cta-description">
                        Connect with fellow graduates, access career resources, and stay involved 
                        with your university community. Your journey continues here.
                    </p>
                    
                    <div class="cta-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Access Your Profile
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus mr-2"></i>
                                Join Alumni Network
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Alumni Sign In
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="main-footer py-12">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-4 mb-4">
                    @if($settings->logo_path)
                        <img src="{{ Storage::url($settings->logo_path) }}" alt="{{ $settings->site_name }}" class="brand-logo">
                    @elseif($settings->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_name }}" class="brand-logo">
                    @endif
                    <h3 class="text-heading-3 text-white">{{ $settings->site_name }}</h3>
                </div>
                
                @if($settings->site_description)
                    <p class="text-body-lg text-white/90 mb-6 max-w-2xl mx-auto">{{ $settings->site_description }}</p>
                @endif
                
                <div class="social-links">
                    @if($settings->facebook_url)
                        <a href="{{ $settings->facebook_url }}" target="_blank" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($settings->twitter_url)
                        <a href="{{ $settings->twitter_url }}" target="_blank" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if($settings->linkedin_url)
                        <a href="{{ $settings->linkedin_url }}" target="_blank" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if($settings->instagram_url)
                        <a href="{{ $settings->instagram_url }}" target="_blank" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                </div>
                
                <div class="border-t border-white/20 mt-8 pt-8 text-center">
                    <p class="text-body-sm text-white/70">
                        &copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved. 
                        Connecting alumni for lifelong academic and professional excellence.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for scroll animations -->
    <script>
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                }
            });
        }, observerOptions);

        // Observe all animatable elements
        document.addEventListener('DOMContentLoaded', () => {
            const animateElements = document.querySelectorAll('.animate-on-scroll');
            animateElements.forEach(el => observer.observe(el));
        });

        // Add floating animation to hero elements
        const floatingElements = document.querySelectorAll('.animate-float');
        floatingElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.2}s`;
        });

        // Header transparency on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(30px)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.1)';
                header.style.backdropFilter = 'blur(20px)';
            }
        });
    </script>
</body>
</html>
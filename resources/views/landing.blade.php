<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BukSU AlumniConnect - Official Alumni Tracking System</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(30, 58, 138, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(241, 165, 18, 0.03) 0%, transparent 50%);
        }
        
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 glass border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('img/buksu-logo.png') }}" alt="BukSU Logo" class="h-12 w-auto" onerror="this.style.display='none'">
                    <div>
                        <div class="text-xl font-display font-bold text-buksu-navy-900">BukSU AlumniConnect</div>
                        <div class="text-xs text-gray-600">Alumni Tracking System</div>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="#features" class="nav-link text-gray-700 hover:text-buksu-navy-800">Features</a>
                    <a href="#about" class="nav-link text-gray-700 hover:text-buksu-navy-800">About</a>
                    <a href="#contact" class="nav-link text-gray-700 hover:text-buksu-navy-800">Contact</a>
                    <div class="h-6 w-px bg-gray-300 mx-2"></div>
                    <a href="{{ route('login') }}" class="nav-link text-gray-700 hover:text-buksu-navy-800">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" class="block px-4 py-2 rounded-lg hover:bg-gray-50">Features</a>
                <a href="#about" class="block px-4 py-2 rounded-lg hover:bg-gray-50">About</a>
                <a href="#contact" class="block px-4 py-2 rounded-lg hover:bg-gray-50">Contact</a>
                <hr class="border-gray-200">
                <a href="{{ route('login') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-50">Sign In</a>
                <a href="{{ route('register') }}" class="block btn btn-primary text-center">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 md:pt-40 md:pb-32 hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Column -->
                <div class="space-y-8">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-buksu-navy-50 border border-buksu-navy-100">
                        <span class="w-2 h-2 bg-buksu-gold-500 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-sm font-medium text-buksu-navy-800">Connecting BukSU Alumni Worldwide</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-display font-bold text-buksu-navy-900 leading-tight">
                        Stay Connected,<br>
                        <span class="text-gradient">Stay Inspired</span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 leading-relaxed max-w-xl">
                        Join the official Bukidnon State University Alumni Tracking System. Connect with fellow alumni, track your career journey, and stay updated with university events.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="btn btn-primary text-lg px-8 py-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Create Account
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-secondary text-lg px-8 py-4">
                            Sign In
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Right Column - Image/Illustration -->
                <div class="relative hidden md:block">
                    <div class="relative z-10">
                        <img src="{{ asset('img/alumni-hero.png') }}" alt="Alumni Illustration" class="rounded-3xl shadow-soft-lg" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 400\'%3E%3Crect fill=\'%23f3f4f6\' width=\'400\' height=\'400\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' font-family=\'Arial\' font-size=\'18\' fill=\'%236b7280\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3EAlumni Network%3C/text%3E%3C/svg%3E'">
                    </div>
                    
                    <!-- Floating Cards -->
                    <div class="absolute -top-6 -left-6 bg-white rounded-2xl shadow-soft-lg p-4 animate-bounce" style="animation-duration: 3s;">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-buksu-gold-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-buksu-gold-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Verified</div>
                                <div class="font-semibold text-buksu-navy-900">Alumni</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl shadow-soft-lg p-4" style="animation: bounce 3s infinite; animation-delay: 1s;">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-buksu-accent-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-buksu-accent-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Upcoming</div>
                                <div class="font-semibold text-buksu-navy-900">Events</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-header">
                <h2 class="section-title">Everything You Need</h2>
                <p class="section-subtitle">Comprehensive tools designed for the BukSU Alumni community</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card feature-card">
                    <div class="card-body space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-buksu-navy-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900">Alumni Directory</h3>
                        <p class="text-gray-600">Connect and network with fellow BukSU graduates from different batches and programs.</p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="card feature-card">
                    <div class="card-body space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-buksu-gold-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-buksu-gold-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900">Career Tracking</h3>
                        <p class="text-gray-600">Share your professional journey and employment history with the alumni community.</p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="card feature-card">
                    <div class="card-body space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-buksu-accent-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-buksu-accent-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900">Events & Updates</h3>
                        <p class="text-gray-600">Stay informed about university events, reunions, and alumni activities.</p>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="card feature-card">
                    <div class="card-body space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-success-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900">Verified Profiles</h3>
                        <p class="text-gray-600">Authentic verification process managed by the Alumni Relations Unit.</p>
                    </div>
                </div>
                
                <!-- Feature 5 -->
                <div class="card feature-card">
                    <div class="card-body space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-warning-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-warning-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900">Support System</h3>
                        <p class="text-gray-600">Get assistance and support from the Alumni Relations Unit team.</p>
                    </div>
                </div>
                
                <!-- Feature 6 -->
                <div class="card feature-card">
                    <div class="card-body space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-error-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-error-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd"></path>
                                <path d="M11 4a1 1 0 10-2 0v1a1 1 0 002 0V4zM10 7a1 1 0 011 1v1h2a1 1 0 110 2h-3a1 1 0 01-1-1V8a1 1 0 011-1zM16 9a1 1 0 100 2 1 1 0 000-2zM9 13a1 1 0 011-1h1a1 1 0 110 2v2a1 1 0 11-2 0v-3zM7 11a1 1 0 100-2H4a1 1 0 100 2h3zM17 13a1 1 0 01-1 1h-2a1 1 0 110-2h2a1 1 0 011 1zM16 17a1 1 0 100-2h-3a1 1 0 100 2h3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900">Data Analytics</h3>
                        <p class="text-gray-600">Comprehensive insights and statistics for institutional reporting.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-buksu-gold-50 border border-buksu-gold-100">
                        <span class="text-sm font-medium text-buksu-gold-700">About the Platform</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl font-display font-bold text-buksu-navy-900">
                        Built for BukSU Alumni
                    </h2>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        The official alumni tracking and networking platform of Bukidnon State University. Designed to strengthen connections, foster professional growth, and maintain lifelong bonds with the university community.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-buksu-navy-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-buksu-navy-900">Official University Platform</h4>
                                <p class="text-gray-600">Sanctioned and operated by Bukidnon State University</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-buksu-navy-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-buksu-navy-900">Secure & Private</h4>
                                <p class="text-gray-600">Your data is protected with enterprise-level security</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-buksu-navy-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-buksu-navy-900">Multi-Tenancy Support</h4>
                                <p class="text-gray-600">Departments can request custom domains for their own portals</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-buksu-navy-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-buksu-navy-900">Always Improving</h4>
                                <p class="text-gray-600">Regular updates based on alumni feedback and needs</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <img src="{{ asset('img/buksu-campus.jpg') }}" alt="BukSU Campus" class="rounded-3xl shadow-soft-lg" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 500\'%3E%3Crect fill=\'%23f3f4f6\' width=\'400\' height=\'500\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' font-family=\'Arial\' font-size=\'18\' fill=\'%236b7280\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3EBukSU Campus%3C/text%3E%3C/svg%3E'">
                </div>
            </div>
        </div>
    </section>

    <!-- Department Domain Request Section -->
    <section class="section bg-gradient-to-br from-buksu-navy-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Column - Image/Illustration -->
                <div class="order-2 md:order-1">
                    <div class="relative">
                        <div class="card p-8 bg-gradient-to-br from-white to-gray-50">
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-16 h-16 rounded-2xl bg-buksu-navy-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-buksu-gold-600">Custom Domain</div>
                                    <div class="text-xl font-display font-bold text-buksu-navy-900">medicine.buksu-alumni.ph</div>
                                </div>
                            </div>
                            <div class="space-y-3 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Dedicated alumni portal
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Department-specific branding
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Independent management
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Content -->
                <div class="order-1 md:order-2 space-y-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-buksu-accent-50 border border-buksu-accent-100">
                        <span class="text-sm font-medium text-buksu-accent-700">For Departments</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl font-display font-bold text-buksu-navy-900">
                        Get Your Own Alumni Portal
                    </h2>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Departments can request their own customized domain and manage their alumni independently. Perfect for colleges, departments, or programs that want a dedicated space for their graduates.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-buksu-navy-100 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700"><span class="font-semibold">Custom Subdomain:</span> Your department gets its own unique URL</p>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-buksu-navy-100 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700"><span class="font-semibold">Full Control:</span> Manage your alumni data and communications</p>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-buksu-navy-100 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700"><span class="font-semibold">Easy Setup:</span> Request approved within 24-48 hours</p>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <a href="{{ route('request-domain') }}" class="btn btn-primary text-lg px-8 py-4 inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            Request Custom Domain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section bg-gradient-primary text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-display font-bold mb-6">
                Ready to Connect?
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Join thousands of BukSU alumni already connected on our platform. Create your profile today and be part of the community.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn bg-white text-buksu-navy-900 hover:bg-gray-100 text-lg px-8 py-4">
                    Create Free Account
                </a>
                <a href="#contact" class="btn bg-white/10 text-white border-2 border-white/20 hover:bg-white/20 text-lg px-8 py-4">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('img/buksu-logo.png') }}" alt="BukSU Logo" class="h-10 w-auto" onerror="this.style.display='none'">
                        <div>
                            <div class="text-lg font-display font-bold text-buksu-navy-900">BukSU AlumniConnect</div>
                            <div class="text-sm text-gray-600">Alumni Tracking System</div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 max-w-md">
                        Official alumni tracking system of Bukidnon State University. Educate. Innovate. Lead.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://facebook.com/BuksuAlumniRelationsUnit" target="_blank" class="w-10 h-10 rounded-lg bg-buksu-navy-100 hover:bg-buksu-navy-200 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-buksu-navy-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="mailto:buksualumni@buksu.edu.ph" class="w-10 h-10 rounded-lg bg-buksu-navy-100 hover:bg-buksu-navy-200 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-display font-semibold text-buksu-navy-900 mb-4">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-600 hover:text-buksu-navy-800 transition-colors">Features</a></li>
                        <li><a href="#about" class="text-gray-600 hover:text-buksu-navy-800 transition-colors">About</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-buksu-navy-800 transition-colors">Sign In</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-buksu-navy-800 transition-colors">Register</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-display font-semibold text-buksu-navy-900 mb-4">Contact</h4>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Bukidnon State University<br>Malaybalay City, Bukidnon</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            buksualumni@buksu.edu.ph
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-center text-gray-600 text-sm">
                    &copy; {{ date('Y') }} Bukidnon State University - AlumniConnect. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Close mobile menu if open
                    document.getElementById('mobileMenu').classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>

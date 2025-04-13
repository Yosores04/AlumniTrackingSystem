<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings->site_name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: {{ $settings->primary_color }};
            --secondary-color: {{ $settings->secondary_color }};
            --accent-color: {{ $settings->accent_color }};
            --background-color: {{ $settings->background_color }};
            --text-color: {{ $settings->text_color }};
        }
        
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .accent-text {
            color: var(--accent-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color);
        }
        
        .bg-secondary {
            background-color: var(--secondary-color);
        }
        
        .bg-accent {
            background-color: var(--accent-color);
        }
        
        .text-primary-color {
            color: var(--primary-color);
        }
        
        .hero-section {
            position: relative;
            background-size: cover;
            background-position: center;
            min-height: 60vh;
            display: flex;
            align-items: center;
            padding: 3rem;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .social-link {
            color: var(--accent-color);
            transition: color 0.3s ease;
        }
        
        .social-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <header class="bg-secondary text-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center">
                @if($settings->logo_path)
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="{{ $settings->site_name }}" class="h-14 mr-4">
                @else
                    <h1 class="text-2xl font-bold">{{ $settings->site_name }}</h1>
                @endif
            </div>
            
            <nav class="hidden md:flex space-x-8">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-white hover:text-primary-color">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-primary-color">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-white hover:text-primary-color">Register</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>
    
    <main>
        <section class="hero-section" style="background-image: url('{{ $settings->background_image_path ? Storage::url($settings->background_image_path) : asset('images/default-background.jpg') }}')">
            <div class="hero-content text-center text-white">
                <h1 class="text-5xl font-bold mb-6">{{ $settings->site_name }}</h1>
                @if($settings->site_description)
                    <p class="text-xl mb-8">{{ $settings->site_description }}</p>
                @endif
                @if($settings->welcome_message)
                    <div class="mb-8 text-lg">
                        {!! nl2br(e($settings->welcome_message)) !!}
                    </div>
                @endif
                <div class="flex justify-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-3 rounded bg-primary text-white font-semibold hover:bg-accent transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 rounded bg-primary text-white font-semibold hover:bg-accent transition-colors">Log In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-3 rounded border-2 border-white text-white font-semibold hover:bg-white hover:text-secondary transition-colors">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>
        
        <section class="py-16 px-4">
            <div class="container mx-auto max-w-6xl">
                <h2 class="text-3xl font-semibold mb-8 text-center">About Our Alumni Network</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-3 text-primary-color">Connect</h3>
                        <p>Connect with fellow alumni from around the world. Build professional relationships and expand your network.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-3 text-primary-color">Grow</h3>
                        <p>Access exclusive resources, job opportunities, and mentorship programs to further your career and personal growth.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-3 text-primary-color">Contribute</h3>
                        <p>Give back to your alma mater by sharing your expertise, mentoring current students, or contributing to scholarships.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer class="bg-secondary text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <h2 class="text-xl font-semibold mb-3">{{ $settings->site_name }}</h2>
                    @if($settings->footer_text)
                        <p class="max-w-md">{!! nl2br(e($settings->footer_text)) !!}</p>
                    @endif
                </div>
                
                @if($settings->show_social_links)
                <div>
                    <h3 class="text-lg font-semibold mb-3">Connect With Us</h3>
                    <div class="flex space-x-4">
                        @if($settings->facebook_url)
                            <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>
                            </a>
                        @endif
                        @if($settings->twitter_url)
                            <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener noreferrer" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6.012a9.35 9.35 0 0 1-2.65.728 4.66 4.66 0 0 0 2.042-2.557 9.33 9.33 0 0 1-2.93 1.12 4.65 4.65 0 0 0-7.92 4.23 13.18 13.18 0 0 1-9.57-4.84 4.65 4.65 0 0 0 1.44 6.2 4.59 4.59 0 0 1-2.1-.58v.06a4.65 4.65 0 0 0 3.73 4.56 4.72 4.72 0 0 1-2.1.08 4.65 4.65 0 0 0 4.35 3.22 9.34 9.34 0 0 1-6.89 1.93 13.14 13.14 0 0 0 7.1 2.08c8.5 0 13.14-7.05 13.14-13.14 0-.2 0-.4-.02-.6a9.4 9.4 0 0 0 2.31-2.39Z"/></svg>
                            </a>
                        @endif
                        @if($settings->instagram_url)
                            <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153.509.5.902 1.105 1.153 1.772.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772c-.5.508-1.105.902-1.772 1.153-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 1.802c-2.67 0-2.986.01-4.04.059-.976.045-1.505.207-1.858.344-.466.182-.8.398-1.15.748-.35.35-.566.684-.748 1.15-.137.353-.3.882-.344 1.857-.048 1.055-.058 1.37-.058 4.041 0 2.67.01 2.986.058 4.04.045.977.207 1.505.344 1.858.182.466.399.8.748 1.15.35.35.684.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058 2.67 0 2.987-.01 4.04-.058.977-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.684.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041 0-2.67-.01-2.986-.058-4.04-.045-.977-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.055-.048-1.37-.058-4.041-.058zm0 3.063a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 8.468a3.333 3.333 0 1 0 0-6.666 3.333 3.333 0 0 0 0 6.666zm6.538-8.671a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/></svg>
                            </a>
                        @endif
                        @if($settings->linkedin_url)
                            <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                <p>&copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 
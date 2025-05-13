<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Instructor Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700|plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <!-- Alpine JS - Load directly to ensure it's available -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
            
            :root {
                /* Brand Colors with RGB variables */
                --primary-color: {{ $settings->primary_color ?? '#0f172a' }};
                --primary-rgb: {{ hex2rgbString($settings->primary_color ?? '#0f172a') }};
                --secondary-color: {{ $settings->secondary_color ?? '#1e293b' }};
                --secondary-rgb: {{ hex2rgbString($settings->secondary_color ?? '#1e293b') }};
                --accent-color: {{ $settings->accent_color ?? '#3b82f6' }};
                --accent-rgb: {{ hex2rgbString($settings->accent_color ?? '#3b82f6') }};
                --background-color: {{ $settings->background_color ?? '#f8fafc' }};
                --background-rgb: {{ hex2rgbString($settings->background_color ?? '#f8fafc') }};
                --text-color: {{ $settings->text_color ?? '#1f2937' }};
                --text-rgb: {{ hex2rgbString($settings->text_color ?? '#1f2937') }};
            }
            
            /* Typography */
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                letter-spacing: -0.01em;
                background-color: var(--background-color);
                color: var(--text-color);
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
                letter-spacing: -0.025em;
                line-height: 1.2;
            }
            
            /* Smooth transitions */
            a, button, input, select, textarea {
                transition: all 0.2s ease-in-out;
            }
            
            /* Background Colors with Transparency */
            .bg-primary { background-color: var(--primary-color); }
            .bg-primary-5 { background-color: rgba(var(--primary-rgb), 0.05); }
            .bg-primary-10 { background-color: rgba(var(--primary-rgb), 0.1); }
            .bg-primary-20 { background-color: rgba(var(--primary-rgb), 0.2); }
            .bg-primary-30 { background-color: rgba(var(--primary-rgb), 0.3); }
            .bg-primary-40 { background-color: rgba(var(--primary-rgb), 0.4); }
            .bg-primary-50 { background-color: rgba(var(--primary-rgb), 0.5); }
            .bg-primary-60 { background-color: rgba(var(--primary-rgb), 0.6); }
            .bg-primary-70 { background-color: rgba(var(--primary-rgb), 0.7); }
            .bg-primary-80 { background-color: rgba(var(--primary-rgb), 0.8); }
            .bg-primary-90 { background-color: rgba(var(--primary-rgb), 0.9); }
            
            .bg-secondary { background-color: var(--secondary-color); }
            .bg-secondary-5 { background-color: rgba(var(--secondary-rgb), 0.05); }
            .bg-secondary-10 { background-color: rgba(var(--secondary-rgb), 0.1); }
            .bg-secondary-20 { background-color: rgba(var(--secondary-rgb), 0.2); }
            .bg-secondary-30 { background-color: rgba(var(--secondary-rgb), 0.3); }
            .bg-secondary-40 { background-color: rgba(var(--secondary-rgb), 0.4); }
            .bg-secondary-50 { background-color: rgba(var(--secondary-rgb), 0.5); }
            .bg-secondary-60 { background-color: rgba(var(--secondary-rgb), 0.6); }
            .bg-secondary-70 { background-color: rgba(var(--secondary-rgb), 0.7); }
            .bg-secondary-80 { background-color: rgba(var(--secondary-rgb), 0.8); }
            .bg-secondary-90 { background-color: rgba(var(--secondary-rgb), 0.9); }
            
            .bg-accent { background-color: var(--accent-color); }
            .bg-accent-5 { background-color: rgba(var(--accent-rgb), 0.05); }
            .bg-accent-10 { background-color: rgba(var(--accent-rgb), 0.1); }
            .bg-accent-20 { background-color: rgba(var(--accent-rgb), 0.2); }
            .bg-accent-30 { background-color: rgba(var(--accent-rgb), 0.3); }
            .bg-accent-40 { background-color: rgba(var(--accent-rgb), 0.4); }
            .bg-accent-50 { background-color: rgba(var(--accent-rgb), 0.5); }
            .bg-accent-60 { background-color: rgba(var(--accent-rgb), 0.6); }
            .bg-accent-70 { background-color: rgba(var(--accent-rgb), 0.7); }
            .bg-accent-80 { background-color: rgba(var(--accent-rgb), 0.8); }
            .bg-accent-90 { background-color: rgba(var(--accent-rgb), 0.9); }
            
            /* Text Colors */
            .text-primary { color: var(--primary-color); }
            .text-secondary { color: var(--secondary-color); }
            .text-accent { color: var(--accent-color); }
            .text-muted { color: rgba(var(--text-rgb), 0.6); }
            
            /* Border Colors */
            .border-primary { border-color: var(--primary-color); }
            .border-primary-10 { border-color: rgba(var(--primary-rgb), 0.1); }
            .border-primary-20 { border-color: rgba(var(--primary-rgb), 0.2); }
            .border-primary-30 { border-color: rgba(var(--primary-rgb), 0.3); }
            
            .border-secondary { border-color: var(--secondary-color); }
            .border-secondary-10 { border-color: rgba(var(--secondary-rgb), 0.1); }
            .border-secondary-20 { border-color: rgba(var(--secondary-rgb), 0.2); }
            .border-secondary-30 { border-color: rgba(var(--secondary-rgb), 0.3); }
            
            .border-accent { border-color: var(--accent-color); }
            .border-accent-10 { border-color: rgba(var(--accent-rgb), 0.1); }
            .border-accent-20 { border-color: rgba(var(--accent-rgb), 0.2); }
            .border-accent-30 { border-color: rgba(var(--accent-rgb), 0.3); }
            
            /* Shadow with brand colors */
            .shadow-primary { box-shadow: 0 4px 6px -1px rgba(var(--primary-rgb), 0.1), 0 2px 4px -1px rgba(var(--primary-rgb), 0.06); }
            .shadow-secondary { box-shadow: 0 4px 6px -1px rgba(var(--secondary-rgb), 0.1), 0 2px 4px -1px rgba(var(--secondary-rgb), 0.06); }
            .shadow-accent { box-shadow: 0 4px 6px -1px rgba(var(--accent-rgb), 0.1), 0 2px 4px -1px rgba(var(--accent-rgb), 0.06); }
            
            /* Navbar styling */
            .main-navbar {
                background-color: #0f172a;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }
            
            /* Card styling */
            .content-card {
                background-color: white;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
                padding: 1.75rem;
                margin-bottom: 1.5rem;
                transition: box-shadow 0.3s ease;
            }
            
            .content-card:hover {
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.12);
            }
            
            /* Section headings */
            .section-heading {
                position: relative;
                color: #0f172a;
                font-weight: 600;
                padding-bottom: 0.5rem;
                margin-bottom: 1.25rem;
            }
            
            .section-heading:after {
                content: '';
                position: absolute;
                left: 0;
                bottom: 0;
                height: 3px;
                width: 40px;
                background-color: var(--accent-color);
                border-radius: 3px;
            }
            
            /* Form inputs */
            .form-control {
                border: 1px solid #cbd5e1;
                border-radius: 0.375rem;
                padding: 0.625rem 0.875rem;
                width: 100%;
                transition: all 0.2s ease;
                font-size: 0.9375rem;
                background-color: white;
                color: #1f2937;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }
            
            .form-control:focus {
                border-color: var(--accent-color);
                outline: none;
                box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.15);
            }
            
            .form-label {
                font-weight: 500;
                color: #374151;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
                display: block;
            }
            
            /* Info cards & panels */
            .info-card {
                border-radius: 0.5rem;
                padding: 1.25rem;
                margin-bottom: 1.5rem;
                border-left: 4px solid var(--accent-color);
                background-color: #f0f9ff;
            }
            
            /* Buttons */
            .btn {
                font-weight: 500;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-primary {
                background-color: var(--accent-color);
                color: white;
                border: 1px solid transparent;
            }
            
            .btn-primary:hover {
                background-color: rgba(var(--accent-rgb), 0.9);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px rgba(var(--accent-rgb), 0.2);
            }
            
            .btn-secondary {
                background-color: #f3f4f6;
                color: #374151;
                border: 1px solid #d1d5db;
            }
            
            .btn-secondary:hover {
                background-color: #e5e7eb;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }
            
            /* Badge styles */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 0.25rem 0.625rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 500;
                line-height: 1;
            }
            
            .badge-success {
                background-color: #d1fae5;
                color: #065f46;
            }
            
            .badge-warning {
                background-color: #fef3c7;
                color: #92400e;
            }
            
            .badge-info {
                background-color: #dbeafe;
                color: #1e40af;
            }
            
            .badge-danger {
                background-color: #fee2e2;
                color: #b91c1c;
            }
            
            /* Navigation Styles */
            .nav-link {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1rem;
                color: rgba(255, 255, 255, 0.85);
                border-radius: 0.375rem;
                margin-right: 0.25rem;
                transition: all 0.15s ease;
            }
            
            .nav-link:hover {
                color: white;
                background-color: rgba(255, 255, 255, 0.1);
            }
            
            .nav-link.active {
                background-color: rgba(255, 255, 255, 0.15);
                color: white;
                font-weight: 500;
            }
            
            .nav-link i {
                margin-right: 0.5rem;
                font-size: 1rem;
            }
            
            /* Tables */
            .data-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }
            
            .data-table th {
                background-color: #f8fafc;
                padding: 0.75rem 1rem;
                font-weight: 600;
                color: #475569;
                border-bottom: 1px solid #e2e8f0;
                text-align: left;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.025em;
            }
            
            .data-table td {
                padding: 1rem;
                border-bottom: 1px solid #e2e8f0;
                vertical-align: middle;
            }
            
            .data-table tr:hover {
                background-color: #f8fafc;
            }
            
            .data-table tbody tr:last-child td {
                border-bottom: none;
            }
            
            /* Avatar */
            .avatar {
                width: 2rem;
                height: 2rem;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                border: 2px solid rgba(255, 255, 255, 0.4);
            }
            
            .avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            /* Profile dropdown */
            .profile-dropdown {
                position: relative;
                z-index: 100;
            }
            
            .profile-dropdown-content {
                position: absolute;
                right: 0;
                top: calc(100% + 0.75rem);
                width: 240px;
                background: white;
                border-radius: 0.75rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                padding: 0.5rem 0;
                z-index: 100;
                border: 1px solid #f3f4f6;
                transition: opacity 0.3s ease, transform 0.3s ease;
                overflow: visible !important;
            }
            
            .profile-dropdown-content::before {
                content: '';
                position: absolute;
                top: -8px;
                right: 24px;
                width: 16px;
                height: 16px;
                background: white;
                transform: rotate(45deg);
                border-left: 1px solid #f3f4f6;
                border-top: 1px solid #f3f4f6;
                z-index: -1;
            }
            
            .profile-dropdown-item {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                color: #1f2937;
                transition: all 0.15s ease;
                font-size: 0.9rem;
            }
            
            .profile-dropdown-item:hover {
                background-color: #f9fafb;
            }
            
            .profile-dropdown-item:first-child {
                border-top-left-radius: 0.5rem;
                border-top-right-radius: 0.5rem;
            }
            
            .profile-dropdown-item:last-child {
                border-bottom-left-radius: 0.5rem;
                border-bottom-right-radius: 0.5rem;
            }
            
            .profile-dropdown-item i {
                margin-right: 0.75rem;
                font-size: 1rem;
                color: #6b7280;
                width: 20px;
                text-align: center;
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased min-h-screen">
        <div class="min-h-screen flex flex-col">
            <nav class="primary-nav bg-secondary-80 border-b border-secondary-30 shadow-md fixed top-0 w-full z-50">
                <div class="container-fluid px-4 py-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            @php
                                use Illuminate\Support\Facades\Storage;
                                $settings = \App\Models\TenantSettings::getSettings();
                                $logoUrl = null;
                                
                                if ($settings->logo_url) {
                                    $logoUrl = $settings->logo_url;
                                } elseif ($settings->logo_path) {
                                    $logoUrl = Storage::url($settings->logo_path);
                                }
                            @endphp

                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" class="h-8 mr-3" alt="{{ $settings->site_name ?? 'Alumni Logo' }}">
                            @else
                                <img src="/img/1.svg" alt="Alumni Logo" class="h-8 mr-3">
                            @endif
                            <span class="font-bold text-lg tracking-tight">Instructor Portal</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('instructor.dashboard') }}" class="nav-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="{{ route('instructor.alumni.index') }}" class="nav-link {{ request()->routeIs('instructor.alumni.index') ? 'active' : '' }}">
                                <i class="fas fa-user-graduate"></i> Alumni
                            </a>
                            <a href="{{ route('instructor.alumni.create') }}" class="nav-link {{ request()->routeIs('instructor.alumni.create') ? 'active' : '' }}">
                                <i class="fas fa-user-plus"></i> Register Alumni
                            </a>
                            <a href="{{ url('/support') }}" class="nav-link {{ request()->is('support*') ? 'active' : '' }}">
                                <i class="fas fa-headset"></i> Support
                            </a>
                            
                            <div class="profile-dropdown ml-4" x-data="{ open: false }">
                                <button @click.stop.prevent="open = !open" type="button" class="flex items-center bg-secondary-70 hover:bg-secondary-60 text-white px-3 py-2 rounded-full focus:outline-none transition-colors">
                                    <div class="avatar bg-white mr-2">
                                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}">
                                    </div>
                                    <span class="mr-1 text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <i class="fas text-xs ml-1" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                </button>
                                
                                <div x-cloak x-show="open" 
                                     @click.outside="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="profile-dropdown-content">
                                    <a href="{{ route('instructor.dashboard') }}" class="profile-dropdown-item" @click.stop>
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                    <a href="#" class="profile-dropdown-item" @click.stop>
                                        <i class="fas fa-user"></i> My Profile
                                    </a>
                                    <a href="{{ url('/support') }}" class="profile-dropdown-item" @click.stop>
                                        <i class="fas fa-headset"></i> Support
                                    </a>
                                    <a href="#" class="profile-dropdown-item" @click.stop>
                                        <i class="fas fa-cog"></i> Account Settings
                                    </a>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="profile-dropdown-item w-full text-left text-red-600 hover:text-red-700 hover:bg-red-50" @click.stop>
                                            <i class="fas fa-sign-out-alt"></i> Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="container-fluid px-4 py-6 flex-grow mt-8">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4">
                <div class="container-fluid px-4">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Alumni Tracking System') }}
                        </div>
                        <div class="flex space-x-4">
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Privacy Policy</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Terms of Service</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Help Center</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        
        <!-- Confirmation Dialog Component -->
        @include('components.confirm-dialog')
        
        @stack('scripts')
        
        <script>
            // Add smooth scroll behavior
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        
                        document.querySelector(this.getAttribute('href')).scrollIntoView({
                            behavior: 'smooth'
                        });
                    });
                });
                
                // Fallback dropdown handler if Alpine.js fails
                const profileDropdownBtn = document.querySelector('.profile-dropdown button');
                const profileDropdownContent = document.querySelector('.profile-dropdown-content');
                
                if (profileDropdownBtn && profileDropdownContent) {
                    // Check if Alpine.js is working properly
                    const parentEl = profileDropdownBtn.closest('[x-data]');
                    const alpineWorking = parentEl && typeof parentEl.__x !== 'undefined';
                    
                    if (!alpineWorking) {
                        console.log('Alpine.js not detected, using fallback dropdown handler');
                        // Remove Alpine attributes to avoid conflicts
                        profileDropdownBtn.removeAttribute('x-on:click');
                        profileDropdownBtn.removeAttribute('@click');
                        profileDropdownContent.removeAttribute('x-show');
                        profileDropdownContent.removeAttribute('@click.outside');
                        
                        // Initially hide the dropdown
                        profileDropdownContent.style.display = 'none';
                        
                        // Toggle dropdown on button click
                        profileDropdownBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            const isVisible = profileDropdownContent.style.display === 'block';
                            profileDropdownContent.style.display = isVisible ? 'none' : 'block';
                        });
                        
                        // Close dropdown when clicking outside
                        document.addEventListener('click', function(e) {
                            if (!profileDropdownBtn.contains(e.target) && !profileDropdownContent.contains(e.target)) {
                                profileDropdownContent.style.display = 'none';
                            }
                        });
                    }
                }
            });
        </script>
    </body>
</html> 
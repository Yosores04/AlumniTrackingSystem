<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700|plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <!-- Alpine JS via CDN to ensure it's available -->
        <script defer src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
            
            :root {
                /* Brand Colors with RGB variables */
                --primary-color: {{ $settings->primary_color ?? '#4338ca' }};
                --primary-rgb: {{ hex2rgbString($settings->primary_color ?? '#4338ca') }};
                --secondary-color: {{ $settings->secondary_color ?? '#1e293b' }};
                --secondary-rgb: {{ hex2rgbString($settings->secondary_color ?? '#1e293b') }};
                --accent-color: {{ $settings->accent_color ?? '#3b82f6' }};
                --accent-rgb: {{ hex2rgbString($settings->accent_color ?? '#3b82f6') }};
                --background-color: {{ $settings->background_color ?? '#f3f4f6' }};
                --background-rgb: {{ hex2rgbString($settings->background_color ?? '#f3f4f6') }};
                --text-color: {{ $settings->text_color ?? '#1f2937' }};
                --text-rgb: {{ hex2rgbString($settings->text_color ?? '#1f2937') }};
            }
            
            /* Added sidebar styles */
            .sidebar {
                height: calc(100vh - 64px);
                overflow-y: auto;
                width: 260px;
            }
            
            .sidebar-collapsed {
                width: 80px;
            }
            
            .main-content {
                margin-left: 260px;
            }
            
            .main-content-sidebar-collapsed {
                margin-left: 80px;
            }
            
            .link-icon {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
                flex-shrink: 0;
            }
            
            /* Typography */
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                letter-spacing: -0.01em;
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
                letter-spacing: -0.025em;
                line-height: 1.2;
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
            
            /* Common Layout Components */
            body {
                background-color: var(--background-color);
                color: var(--text-color);
            }
            
            /* App Card Styles */
            .app-card {
                @apply bg-white dark:bg-secondary-80 overflow-hidden rounded-lg shadow-md;
            }
            
            .app-card-header {
                @apply p-4 bg-secondary-5 border-b border-secondary-20;
            }
            
            .app-card-body {
                @apply p-6;
            }
            
            .app-card-footer {
                @apply p-4 bg-secondary-5 border-t border-secondary-20;
            }
            
            /* Form Styles */
            .form-label {
                @apply block text-sm font-medium mb-1;
            }
            
            .form-input {
                @apply w-full px-3 py-2 border border-secondary-30 rounded-md focus:outline-none focus:ring focus:border-primary;
            }
            
            .form-textarea {
                @apply w-full px-3 py-2 border border-secondary-30 rounded-md focus:outline-none focus:ring focus:border-primary;
            }
            
            .section-heading {
                @apply text-xl font-medium pb-2 border-b border-secondary-20;
            }
            
            /* Button Styles */
            .btn {
                @apply px-4 py-2 rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition;
            }
            
            .btn-primary {
                @apply bg-primary text-white hover:bg-primary-80 focus:ring-primary;
            }
            
            .btn-secondary {
                @apply bg-secondary text-white hover:bg-secondary-80 focus:ring-secondary;
            }
            
            .btn-accent {
                @apply bg-accent text-white hover:bg-accent-80 focus:ring-accent;
            }
            
            .btn-outline {
                @apply bg-transparent border border-current;
            }
            
            /* Dashboard Cards */
            .dashboard-card-primary {
                @apply app-card relative overflow-hidden border-l-4 border-primary;
            }
            
            .dashboard-card-secondary {
                @apply app-card relative overflow-hidden border-l-4 border-secondary;
            }
            
            .dashboard-card-accent {
                @apply app-card relative overflow-hidden border-l-4 border-accent;
            }
            
            /* Navigation Link */
            .nav-link {
                @apply inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out;
            }
            
            /* Media query for mobile responsiveness */
            @media (max-width: 768px) {
                .sidebar {
                    width: 80px;
                }
                
                .main-content {
                    margin-left: 80px;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <div class="pt-16 flex flex-1">
                <!-- Simplified Sidebar -->
                <aside class="sidebar bg-white shadow-sm border-r border-gray-200 fixed left-0 top-16 z-40">
                    <div class="py-6 h-full overflow-y-auto">
                        <div class="px-4 mb-6">
                            <h2 class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Quick Actions</h2>
                        </div>
                        
                        <nav class="space-y-1 px-2">
                            <a href="{{ route('tenant.profile.edit') }}" class="group flex items-center px-3 py-3 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                                <div class="link-icon bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Update Profile</div>
                                    <div class="text-xs text-gray-500">Manage your details</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('tenant.jobs.index') }}" class="group flex items-center px-3 py-3 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                <div class="link-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Career Opportunities</div>
                                    <div class="text-xs text-gray-500">Browse job listings</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('tenant.events.index') }}" class="group flex items-center px-3 py-3 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                <div class="link-icon bg-green-100 text-green-600">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Events Calendar</div>
                                    <div class="text-xs text-gray-500">Upcoming gatherings</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('tenant.news.index') }}" class="group flex items-center px-3 py-3 rounded-lg hover:bg-yellow-50 transition-colors duration-200">
                                <div class="link-icon bg-yellow-100 text-yellow-600">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Latest News</div>
                                    <div class="text-xs text-gray-500">Stay updated</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('tenant.directory.index') }}" class="group flex items-center px-3 py-3 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                <div class="link-icon bg-red-100 text-red-600">
                                    <i class="fas fa-address-book"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Alumni Network</div>
                                    <div class="text-xs text-gray-500">Connect with graduates</div>
                                </div>
                            </a>
                        </nav>
                    </div>
                </aside>

                <!-- Main Content with sidebar padding -->
                <div class="main-content flex-1 transition-all duration-300">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-secondary shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main class="py-4 px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
        
        <script>
            // Simple JavaScript to handle mobile responsiveness
            document.addEventListener('DOMContentLoaded', function() {
                function handleResize() {
                    if (window.innerWidth <= 768) {
                        document.querySelectorAll('.sidebar').forEach(el => {
                            el.classList.add('sidebar-collapsed');
                        });
                        document.querySelectorAll('.main-content').forEach(el => {
                            el.classList.add('main-content-sidebar-collapsed');
                        });
                    } else {
                        document.querySelectorAll('.sidebar').forEach(el => {
                            el.classList.remove('sidebar-collapsed');
                        });
                        document.querySelectorAll('.main-content').forEach(el => {
                            el.classList.remove('main-content-sidebar-collapsed');
                        });
                    }
                }
                
                // Initial call
                handleResize();
                
                // Add event listener
                window.addEventListener('resize', handleResize);
            });
        </script>
    </body>
</html>

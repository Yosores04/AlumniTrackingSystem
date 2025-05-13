<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - Alumni Portal</title>

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
            
            /* Navigation styles */
            .primary-nav {
                background-color: var(--secondary-color);
                color: white;
            }
            
            .nav-link {
                color: rgba(255, 255, 255, 0.8);
                transition: color 0.3s, background-color 0.3s;
                padding: 0.5rem 0.75rem;
                border-radius: 0.375rem;
            }
            
            .nav-link:hover {
                color: white;
                background-color: rgba(255, 255, 255, 0.1);
            }
            
            .nav-link.active {
                color: white;
                background-color: rgba(var(--primary-rgb), 0.7);
            }
        </style>
    </head>
    <body class="font-sans antialiased min-h-screen">
        <nav x-data="{ open: false }" class="primary-nav border-b border-secondary-30 shadow-md fixed top-0 w-full z-50">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('alumni.dashboard') }}" class="flex items-center">
                                @php
                                    use Illuminate\Support\Facades\Storage;
                                    $logoSettings = \App\Models\TenantSettings::getSettings();
                                    $logoUrl = null;
                                    
                                    if ($logoSettings->logo_url) {
                                        $logoUrl = $logoSettings->logo_url;
                                    } elseif ($logoSettings->logo_path) {
                                        $logoUrl = Storage::url($logoSettings->logo_path);
                                    }
                                @endphp

                                @if ($logoUrl)
                                    <img src="{{ $logoUrl }}" class="h-8 w-auto mr-2" alt="{{ $logoSettings->site_name ?? 'Alumni Logo' }}">
                                @else
                                    <img src="/img/1.svg" class="h-8 w-auto mr-2" alt="Alumni Logo">
                                @endif
                                <span class="font-bold text-lg text-white tracking-tight">Alumni Portal</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                            <a href="{{ route('alumni.dashboard') }}" class="nav-link flex items-center {{ request()->routeIs('alumni.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt mr-1.5"></i>
                                {{ __('Dashboard') }}
                            </a>
                            
                            <a href="{{ route('alumni.profile') }}" class="nav-link flex items-center {{ request()->routeIs('alumni.profile') ? 'active' : '' }}">
                                <i class="fas fa-user-circle mr-1.5"></i>
                                {{ __('My Profile') }}
                            </a>
                            
                            <a href="{{ url('/support') }}" class="nav-link flex items-center {{ request()->is('support*') ? 'active' : '' }}">
                                <i class="fas fa-headset mr-1.5"></i>
                                {{ __('Support') }}
                            </a>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="nav-link inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" x-cloak>
                                <a href="{{ route('alumni.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2 text-primary"></i>
                                    {{ __('Profile') }}
                                </a>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2 text-primary"></i>
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-primary-30 focus:outline-none focus:bg-primary-30 focus:text-white transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('alumni.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('alumni.dashboard') ? 'border-primary text-primary bg-primary-5' : 'border-transparent text-white hover:bg-primary-30' }} text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        {{ __('Dashboard') }}
                    </a>
                    
                    <a href="{{ route('alumni.profile') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('alumni.profile') ? 'border-primary text-primary bg-primary-5' : 'border-transparent text-white hover:bg-primary-30' }} text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                        <i class="fas fa-user-circle mr-2"></i>
                        {{ __('My Profile') }}
                    </a>
                    
                    <a href="{{ url('/support') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('support*') ? 'border-primary text-primary bg-primary-5' : 'border-transparent text-white hover:bg-primary-30' }} text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                        <i class="fas fa-headset mr-2"></i>
                        {{ __('Support') }}
                    </a>
                    
                    <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:bg-primary-30 text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        {{ __('Alumni Directory') }}
                    </a>
                    
                    <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:bg-primary-30 text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                        <i class="fas fa-briefcase mr-2"></i>
                        {{ __('Job Opportunities') }}
                    </a>
                    
                    <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:bg-primary-30 text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ __('Events') }}
                    </a>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-secondary-30">
                    <div class="px-4">
                        <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-secondary-20">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <a href="{{ route('alumni.profile') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:bg-primary-30 text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>
                            {{ __('Profile') }}
                        </a>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block pl-3 pr-4 py-2 border-l-4 border-transparent text-white hover:bg-primary-30 text-base font-medium focus:outline-none focus:text-primary focus:bg-primary-5 focus:border-primary transition duration-150 ease-in-out flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Add padding to main content to account for fixed navbar -->
        <div class="pt-16">
            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html> 
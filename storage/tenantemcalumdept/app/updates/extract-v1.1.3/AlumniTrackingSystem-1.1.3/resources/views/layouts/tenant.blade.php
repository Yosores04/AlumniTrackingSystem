<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Alumni Tracking System') }}</title>

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
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html> 
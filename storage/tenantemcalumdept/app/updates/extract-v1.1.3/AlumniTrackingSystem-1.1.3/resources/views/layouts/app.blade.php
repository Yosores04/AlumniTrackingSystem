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
                @apply block text-sm font-medium mb-1 text-gray-700;
            }
            
            .form-control {
                @apply w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-40 focus:border-primary transition-all;
            }
            
            select.form-control {
                @apply pr-10 appearance-none bg-no-repeat bg-right;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-size: 1.5em 1.5em;
            }
            
            textarea.form-control {
                @apply resize-none;
            }
            
            /* Enhanced Card Styles */
            .content-card {
                @apply bg-white rounded-lg border border-gray-200 shadow-sm p-6 mb-6 transition-all duration-300;
            }
            
            .content-card:hover {
                @apply shadow-md border-gray-300;
            }
            
            /* Button Styles */
            .btn {
                @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
            }
            
            .btn-primary {
                @apply bg-primary hover:bg-primary-80 text-white focus:ring-primary-40;
            }
            
            .btn-secondary {
                @apply bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-primary-30;
            }
            
            .btn-danger {
                @apply bg-red-600 hover:bg-red-700 text-white focus:ring-red-500;
            }
            
            .btn-sm {
                @apply px-3 py-1.5 text-xs;
            }
            
            /* Table styles */
            .data-table {
                @apply w-full divide-y divide-gray-200 bg-white rounded-lg overflow-hidden border border-gray-200;
            }
            
            .data-table thead {
                @apply bg-gray-50;
            }
            
            .data-table th {
                @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
            }
            
            .data-table tbody {
                @apply divide-y divide-gray-200;
            }
            
            .data-table tbody tr {
                @apply hover:bg-gray-50 transition-colors duration-150;
            }
            
            .data-table td {
                @apply px-6 py-4 whitespace-nowrap text-sm;
            }
            
            /* Badge styles */
            .badge {
                @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
            }
            
            .badge-success {
                @apply bg-green-100 text-green-800;
            }
            
            .badge-warning {
                @apply bg-yellow-100 text-yellow-800;
            }
            
            .badge-info {
                @apply bg-blue-100 text-blue-800;
            }
            
            .badge-danger {
                @apply bg-red-100 text-red-800;
            }
            
            /* Section styles */
            .section-divider {
                @apply border-b border-gray-200 my-6;
            }
            
            .section-header {
                @apply flex items-center justify-between mb-4;
            }
            
            /* Avatar improvements */
            .avatar {
                @apply relative inline-block rounded-full overflow-hidden bg-gray-100 flex-shrink-0;
            }
            
            .avatar-sm {
                @apply w-8 h-8;
            }
            
            .avatar-md {
                @apply w-12 h-12;
            }
            
            .avatar-lg {
                @apply w-20 h-20;
            }
            
            /* Notification styles */
            .notification {
                @apply bg-white rounded-md border-l-4 p-4 shadow-sm mb-4;
            }
            
            .notification-info {
                @apply border-blue-500 bg-blue-50;
            }
            
            .notification-success {
                @apply border-green-500 bg-green-50;
            }
            
            .notification-warning {
                @apply border-yellow-500 bg-yellow-50;
            }
            
            .notification-error {
                @apply border-red-500 bg-red-50;
            }
            
            /* Animation utilities */
            .animate-fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
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
                    <div>
                        @if (isset($header))
                            <header class="bg-secondary shadow">
                                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif

                        <!-- Page Content -->
                        <main class="container-fluid px-4 py-6 flex-grow mt-8">
                            @yield('content')
                        </main>
                    </div>
        </div>
        
        <!-- Confirmation Dialog Component -->
        @include('components.confirm-dialog')
        
        <!-- Upgrade Request Modal Component -->
        @include('components.upgrade-request-modal')
        
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

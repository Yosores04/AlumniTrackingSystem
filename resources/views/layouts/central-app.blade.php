<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Alumni Tracking System') }} - Central Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary: #4F46E5;
                --primary-hover: #4338CA;
                --primary-light: #EEF2FF;
                --secondary: #0F172A;
                --secondary-hover: #1E293B;
                --accent: #F59E0B;
                --accent-hover: #D97706;
                --danger: #DC2626;
                --success: #10B981;
                --warning: #FBBF24;
                --info: #0EA5E9;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                background-color: #F9FAFB;
            }
            
            .nav-link {
                color: rgba(255, 255, 255, 0.7);
                transition: color 0.2s;
            }
            
            .nav-link:hover {
                color: rgba(255, 255, 255, 1);
            }
            
            .nav-link.active {
                color: white;
                font-weight: 600;
            }
            
            .primary-nav {
                background-color: var(--secondary);
            }
            
            /* Enhanced card styling */
            .app-card {
                @apply bg-white overflow-hidden shadow-lg rounded-xl transition-all duration-300;
            }
            
            .app-card:hover {
                @apply shadow-xl;
            }
            
            .app-card-header {
                @apply px-6 py-4;
            }
            
            .app-card-header-primary {
                @apply bg-gradient-to-r from-blue-600 to-blue-800 text-white font-bold text-xl;
            }
            
            .app-card-header-purple {
                @apply bg-gradient-to-r from-purple-600 to-purple-800 text-white font-bold text-xl;
            }
            
            .app-card-header-emerald {
                @apply bg-gradient-to-r from-emerald-600 to-emerald-800 text-white font-bold text-xl;
            }
            
            .app-card-body {
                @apply p-6 border-b border-gray-200;
            }
            
            /* Enhanced form controls */
            .app-input {
                @apply w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500;
            }
            
            .app-button {
                @apply inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2;
            }
            
            .app-button-primary {
                @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500;
            }
            
            .app-button-danger {
                @apply bg-red-600 text-white hover:bg-red-700 focus:ring-red-500;
            }
            
            .app-button-success {
                @apply bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500;
            }
            
            .app-button-secondary {
                @apply bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-300;
            }
            
            /* Enhanced alerts */
            .app-alert {
                @apply p-4 mb-6 rounded-lg shadow-md;
            }
            
            .app-alert-success {
                @apply bg-green-100 border-l-4 border-green-500 text-green-700;
            }
            
            .app-alert-danger {
                @apply bg-red-100 border-l-4 border-red-500 text-red-700;
            }
            
            .app-alert-warning {
                @apply bg-amber-100 border-l-4 border-amber-500 text-amber-700;
            }
            
            .app-alert-info {
                @apply bg-blue-100 border-l-4 border-blue-500 text-blue-700;
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.central-navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-primary">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Flash Messages -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                @if (session('success'))
                    <div class="app-alert app-alert-success" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="app-alert app-alert-danger" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="app-alert app-alert-warning" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-amber-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-medium">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div class="app-alert app-alert-info" role="alert">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-blue-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-medium">{{ session('info') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-12 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-sm text-gray-500 mb-4 md:mb-0">
                            &copy; {{ date('Y') }} Alumni Tracking System - Central Administration
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                <i class="fas fa-question-circle mr-1"></i> Help
                            </a>
                            <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                <i class="fas fa-lock mr-1"></i> Privacy Policy
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Scripts -->
        <script>
            // Close alert messages
            document.addEventListener('DOMContentLoaded', function() {
                const closeButtons = document.querySelectorAll('[role="alert"] svg[role="button"]');
                closeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('[role="alert"]').remove();
                    });
                });
            });
        </script>
        
        @stack('scripts')
    </body>
</html> 
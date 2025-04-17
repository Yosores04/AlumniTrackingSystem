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
                --success: #16A34A;
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
        </style>
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

            <!-- Flash Message -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-12 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            &copy; {{ date('Y') }} Alumni Tracking System - Central Administration
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-question-circle"></i> Help
                            </a>
                            <a href="#" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-lock"></i> Privacy Policy
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
    </body>
</html> 
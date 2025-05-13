<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Alumni Tracking System') }} - Domain Suspended</title>

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
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-primary">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
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
                            &copy; {{ date('Y') }} Alumni Tracking System
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
    </body>
</html> 
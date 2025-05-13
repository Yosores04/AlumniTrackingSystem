<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Chart.js for dashboard -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Custom Colors -->
        <style>
            :root {
                /* Brand Colors */
                --brand-primary: {{ $settings->primary_color ?? '#4f46e5' }};
                --brand-secondary: {{ $settings->secondary_color ?? '#1f2937' }};
                
                /* Interface Colors */
                --accent-color: {{ $settings->accent_color ?? '#06b6d4' }};
                
                /* Content Colors */
                --content-bg: {{ $settings->background_color ?? '#ffffff' }};
                --content-text: {{ $settings->text_color ?? '#111827' }};
                
                /* RGB values for rgba usage */
                --brand-primary-rgb: {{ isset($settings->primary_color) ? hex2rgbString($settings->primary_color) : '79, 70, 229' }};
                --brand-secondary-rgb: {{ isset($settings->secondary_color) ? hex2rgbString($settings->secondary_color) : '31, 41, 55' }};
                --accent-color-rgb: {{ isset($settings->accent_color) ? hex2rgbString($settings->accent_color) : '6, 182, 212' }};
                --content-bg-rgb: {{ isset($settings->background_color) ? hex2rgbString($settings->background_color) : '255, 255, 255' }};
                --content-text-rgb: {{ isset($settings->text_color) ? hex2rgbString($settings->text_color) : '17, 24, 39' }};
                
                /* Derived Colors */
                --primary-hover: color-mix(in srgb, var(--brand-primary) 85%, black);
                --secondary-hover: color-mix(in srgb, var(--brand-secondary) 85%, black);
                --accent-hover: color-mix(in srgb, var(--accent-color) 85%, black);
                --brand-primary-light: color-mix(in srgb, var(--brand-primary) 15%, white);
                --brand-secondary-light: color-mix(in srgb, var(--brand-secondary) 15%, white);
                --text-primary: var(--content-text);
                
                /* Navigation and Header Colors */
                --header-bg: var(--brand-secondary);
                --header-text: white;
                --nav-bg: var(--brand-secondary);
                --nav-active: var(--brand-primary);
                
                /* Card and Panel Colors */
                --card-bg: var(--content-bg);
                --card-border: rgba(var(--content-text-rgb), 0.1);
                --card-shadow: rgba(var(--brand-secondary-rgb), 0.1);
                
                /* Button Colors */
                --btn-text: white;
                --btn-disabled: #9ca3af;
            }

            /* Global Styles */
            body {
                background-color: var(--content-bg);
                color: var(--content-text);
                background-image: linear-gradient(135deg, 
                    color-mix(in srgb, var(--brand-secondary) 3%, var(--content-bg)) 0%, 
                    color-mix(in srgb, var(--brand-primary) 3%, var(--content-bg)) 100%);
                min-height: 100vh;
            }

            /* Page Header Style */
            .page-header {
                background-color: var(--brand-secondary);
                color: var(--header-text);
                padding: 1.5rem 0;
            }

            /* Navigation Styles */
            nav.primary-nav {
                background-color: var(--nav-bg);
                color: var(--header-text);
            }
            
            .nav-link {
                color: rgba(255, 255, 255, 0.8);
                transition: color 0.2s;
            }
            
            .nav-link:hover {
                color: white;
            }
            
            .nav-link.active {
                color: white;
                border-color: var(--brand-primary);
            }

            /* Card Styles */
            .app-card {
                background-color: var(--card-bg);
                border-radius: 0.5rem;
                border: 1px solid var(--card-border);
                box-shadow: 0 4px 6px rgba(var(--brand-secondary-rgb), 0.1);
                overflow: hidden;
            }
            
            .app-card-header {
                padding: 1rem 1.5rem;
                border-bottom: 1px solid var(--card-border);
                font-weight: 600;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .app-card-body {
                padding: 1.5rem;
            }
            
            .app-card-footer {
                padding: 1rem 1.5rem;
                border-top: 1px solid var(--card-border);
                background-color: rgba(var(--brand-secondary-rgb), 0.03);
            }

            /* Stats Card */
            .stats-card {
                background: linear-gradient(135deg, 
                    rgba(var(--brand-primary-rgb), 0.9) 0%, 
                    rgba(var(--brand-primary-rgb), 0.7) 100%);
                color: white;
                border: none;
                padding: 1.5rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(var(--brand-secondary-rgb), 0.1);
            }
            
            /* Dashboard Card Variations */
            .dashboard-card-primary {
                background: linear-gradient(135deg, 
                    rgba(var(--brand-primary-rgb), 0.85) 0%, 
                    rgba(var(--brand-primary-rgb), 0.7) 100%);
                color: white;
            }
            
            .dashboard-card-secondary {
                background: linear-gradient(135deg, 
                    rgba(var(--brand-secondary-rgb), 0.85) 0%, 
                    rgba(var(--brand-secondary-rgb), 0.7) 100%);
                color: white;
            }
            
            .dashboard-card-accent {
                background: linear-gradient(135deg, 
                    rgba(var(--accent-color-rgb), 0.85) 0%, 
                    rgba(var(--accent-color-rgb), 0.7) 100%);
                color: white;
            }

            /* Form Elements */
            .form-input,
            .form-select,
            .form-textarea {
                border: 1px solid var(--card-border);
                border-radius: 0.375rem;
                padding: 0.5rem 0.75rem;
                background-color: var(--content-bg);
                color: var(--content-text);
                width: 100%;
            }
            
            .form-input:focus,
            .form-select:focus,
            .form-textarea:focus {
                border-color: rgba(var(--brand-primary-rgb), 0.5);
                outline: none;
                box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.25);
            }
            
            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: var(--content-text);
            }

            /* Buttons */
            .btn {
                display: inline-block;
                padding: 0.5rem 1rem;
                font-weight: 500;
                text-align: center;
                border-radius: 0.375rem;
                transition: all 0.2s;
                cursor: pointer;
            }
            
            /* Override Tailwind Button Colors */
            .btn-primary {
                background-color: var(--brand-primary) !important;
                color: var(--btn-text) !important;
            }
            .btn-primary:hover {
                background-color: var(--primary-hover) !important;
            }
            
            .btn-secondary {
                background-color: var(--brand-secondary) !important;
                color: var(--btn-text) !important;
            }
            .btn-secondary:hover {
                background-color: var(--secondary-hover) !important;
            }
            
            .btn-accent {
                background-color: var(--accent-color) !important;
                color: var(--btn-text) !important;
            }
            .btn-accent:hover {
                background-color: var(--accent-hover) !important;
            }
            
            .btn-outline-primary {
                background-color: transparent !important;
                color: var(--brand-primary) !important;
                border: 1px solid var(--brand-primary) !important;
            }
            .btn-outline-primary:hover {
                background-color: rgba(var(--brand-primary-rgb), 0.1) !important;
            }
            
            .btn-outline-secondary {
                background-color: transparent !important;
                color: var(--brand-secondary) !important;
                border: 1px solid var(--brand-secondary) !important;
            }
            .btn-outline-secondary:hover {
                background-color: rgba(var(--brand-secondary-rgb), 0.1) !important;
            }
            
            /* Badge Styles */
            .badge {
                display: inline-block;
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
                font-weight: 600;
                border-radius: 9999px;
            }
            
            .badge-primary {
                background-color: rgba(var(--brand-primary-rgb), 0.1);
                color: var(--brand-primary);
            }
            
            .badge-secondary {
                background-color: rgba(var(--brand-secondary-rgb), 0.1);
                color: var(--brand-secondary);
            }
            
            .badge-accent {
                background-color: rgba(var(--accent-color-rgb), 0.1);
                color: var(--accent-color);
            }
            
            /* Table Styles */
            .app-table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .app-table th {
                text-align: left;
                padding: 0.75rem 1rem;
                font-weight: 600;
                border-bottom: 2px solid var(--card-border);
            }
            
            .app-table td {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid var(--card-border);
            }
            
            .app-table tr:last-child td {
                border-bottom: none;
            }
            
            .app-table-striped tr:nth-child(even) {
                background-color: rgba(var(--brand-secondary-rgb), 0.03);
            }
            
            .app-table-hover tr:hover {
                background-color: rgba(var(--brand-primary-rgb), 0.05);
            }
            
            /* Text Colors */
            .text-primary {
                color: var(--brand-primary) !important;
            }
            .text-secondary {
                color: var(--brand-secondary) !important;
            }
            .text-accent {
                color: var(--accent-color) !important;
            }
            .text-muted {
                color: rgba(var(--content-text-rgb), 0.6) !important;
            }

            /* Background Colors with Transparency */
            .bg-primary-10 { background-color: rgba(var(--brand-primary-rgb), 0.1) !important; }
            .bg-primary-20 { background-color: rgba(var(--brand-primary-rgb), 0.2) !important; }
            .bg-primary-30 { background-color: rgba(var(--brand-primary-rgb), 0.3) !important; }
            .bg-primary-40 { background-color: rgba(var(--brand-primary-rgb), 0.4) !important; }
            .bg-primary-50 { background-color: rgba(var(--brand-primary-rgb), 0.5) !important; }
            .bg-primary-60 { background-color: rgba(var(--brand-primary-rgb), 0.6) !important; }
            .bg-primary-70 { background-color: rgba(var(--brand-primary-rgb), 0.7) !important; }
            .bg-primary-80 { background-color: rgba(var(--brand-primary-rgb), 0.8) !important; }
            .bg-primary-90 { background-color: rgba(var(--brand-primary-rgb), 0.9) !important; }

            .bg-secondary-10 { background-color: rgba(var(--brand-secondary-rgb), 0.1) !important; }
            .bg-secondary-20 { background-color: rgba(var(--brand-secondary-rgb), 0.2) !important; }
            .bg-secondary-30 { background-color: rgba(var(--brand-secondary-rgb), 0.3) !important; }
            .bg-secondary-40 { background-color: rgba(var(--brand-secondary-rgb), 0.4) !important; }
            .bg-secondary-50 { background-color: rgba(var(--brand-secondary-rgb), 0.5) !important; }
            .bg-secondary-60 { background-color: rgba(var(--brand-secondary-rgb), 0.6) !important; }
            .bg-secondary-70 { background-color: rgba(var(--brand-secondary-rgb), 0.7) !important; }
            .bg-secondary-80 { background-color: rgba(var(--brand-secondary-rgb), 0.8) !important; }
            .bg-secondary-90 { background-color: rgba(var(--brand-secondary-rgb), 0.9) !important; }

            .bg-accent-10 { background-color: rgba(var(--accent-color-rgb), 0.1) !important; }
            .bg-accent-20 { background-color: rgba(var(--accent-color-rgb), 0.2) !important; }
            .bg-accent-30 { background-color: rgba(var(--accent-color-rgb), 0.3) !important; }
            .bg-accent-40 { background-color: rgba(var(--accent-color-rgb), 0.4) !important; }
            .bg-accent-50 { background-color: rgba(var(--accent-color-rgb), 0.5) !important; }
            .bg-accent-60 { background-color: rgba(var(--accent-color-rgb), 0.6) !important; }
            .bg-accent-70 { background-color: rgba(var(--accent-color-rgb), 0.7) !important; }
            .bg-accent-80 { background-color: rgba(var(--accent-color-rgb), 0.8) !important; }
            .bg-accent-90 { background-color: rgba(var(--accent-color-rgb), 0.9) !important; }

            /* Text colors with transparency */
            .text-primary-60 { color: rgba(var(--brand-primary-rgb), 0.6) !important; }
            .text-primary-70 { color: rgba(var(--brand-primary-rgb), 0.7) !important; }
            .text-primary-80 { color: rgba(var(--brand-primary-rgb), 0.8) !important; }
            .text-primary-90 { color: rgba(var(--brand-primary-rgb), 0.9) !important; }

            /* Border colors with transparency */
            .border-primary-10 { border-color: rgba(var(--brand-primary-rgb), 0.1) !important; }
            .border-primary-20 { border-color: rgba(var(--brand-primary-rgb), 0.2) !important; }
            .border-primary-30 { border-color: rgba(var(--brand-primary-rgb), 0.3) !important; }
            .border-primary-40 { border-color: rgba(var(--brand-primary-rgb), 0.4) !important; }
            .border-primary-50 { border-color: rgba(var(--brand-primary-rgb), 0.5) !important; }
            
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.05);
            }
            
            ::-webkit-scrollbar-thumb {
                background-color: var(--brand-primary);
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background-color: var(--primary-hover);
            }
            
            /* Page headers with consistent styling */
            .section-heading {
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--content-text);
                margin-bottom: 1.5rem;
                padding-bottom: 0.75rem;
                border-bottom: 1px solid rgba(var(--brand-secondary-rgb), 0.1);
            }
        </style>
    </head>
    <body class="font-sans antialiased min-h-screen">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-secondary-80 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-4">
                {{ $slot }}
            </main>
        </div>

        <script>
            // Function to convert hex color to RGB values
            function hexToRgb(hex) {
                // Remove the hash if it exists
                hex = hex.replace(/^#/, '');
                
                // Parse the hex values
                let r, g, b;
                if (hex.length === 3) {
                    // For shorthand hex (e.g. #FFF)
                    r = parseInt(hex.charAt(0) + hex.charAt(0), 16);
                    g = parseInt(hex.charAt(1) + hex.charAt(1), 16);
                    b = parseInt(hex.charAt(2) + hex.charAt(2), 16);
                } else {
                    // For full hex (e.g. #FFFFFF)
                    r = parseInt(hex.substring(0, 2), 16);
                    g = parseInt(hex.substring(2, 4), 16);
                    b = parseInt(hex.substring(4, 6), 16);
                }
                
                return { r, g, b };
            }
            
            // Function to update RGB variables based on current CSS color variables
            function updateRgbVariables() {
                const root = document.documentElement;
                const style = getComputedStyle(root);
                
                // Get the color values from CSS variables
                const brandPrimary = style.getPropertyValue('--brand-primary').trim();
                const brandSecondary = style.getPropertyValue('--brand-secondary').trim();
                const accentColor = style.getPropertyValue('--accent-color').trim();
                const contentBg = style.getPropertyValue('--content-bg').trim();
                const textPrimary = style.getPropertyValue('--text-primary').trim();
                const textSecondary = style.getPropertyValue('--text-secondary').trim();
                const textTertiary = style.getPropertyValue('--text-tertiary').trim();
                
                // Convert and set RGB variables
                if (brandPrimary) {
                    const rgb = hexToRgb(brandPrimary);
                    root.style.setProperty('--brand-primary-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                }
                
                if (brandSecondary) {
                    const rgb = hexToRgb(brandSecondary);
                    root.style.setProperty('--brand-secondary-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                }
                
                if (accentColor) {
                    const rgb = hexToRgb(accentColor);
                    root.style.setProperty('--accent-color-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                }
                
                if (contentBg) {
                    const rgb = hexToRgb(contentBg);
                    root.style.setProperty('--content-bg-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                }
                
                if (textPrimary) {
                    const rgb = hexToRgb(textPrimary);
                    root.style.setProperty('--text-primary-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                }
                
                if (textSecondary) {
                    const rgb = hexToRgb(textSecondary);
                    root.style.setProperty('--text-secondary-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                }
                
                if (textTertiary) {
                    const rgb = hexToRgb(textTertiary);
                    root.style.setProperty('--text-tertiary-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
                }
            }
            
            // Run when DOM is fully loaded
            document.addEventListener('DOMContentLoaded', function() {
                updateRgbVariables();
            });
        </script>
    </body>
</html> 
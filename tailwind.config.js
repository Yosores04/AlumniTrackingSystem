import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'system-ui', '-apple-system', ...defaultTheme.fontFamily.sans],
                display: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // BukSU Alumni Relations Unit Brand Colors
                buksu: {
                    navy: {
                        50: '#f0f4ff',
                        100: '#e0e9ff',
                        200: '#c7d7ff',
                        300: '#a3baff',
                        400: '#7d93ff',
                        500: '#5b6fff',
                        600: '#4146f5',
                        700: '#2d2b7e',
                        800: '#1a1d4a',
                        900: '#0f1229',
                        950: '#0a0b1a',
                    },
                    gold: {
                        50: '#fefaec',
                        100: '#fdf2ca',
                        200: '#fbe58f',
                        300: '#f9d455',
                        400: '#f7c42c',
                        500: '#f1a512',
                        600: '#d5800c',
                        700: '#b15b0d',
                        800: '#8f4612',
                        900: '#763b12',
                        950: '#441d06',
                    },
                    accent: {
                        50: '#f0f9ff',
                        100: '#e0f2fe',
                        200: '#bae6fd',
                        300: '#7dd3fc',
                        400: '#38bdf8',
                        500: '#0ea5e9',
                        600: '#0284c7',
                        700: '#0369a1',
                        800: '#075985',
                        900: '#0c4a6e',
                        950: '#082f49',
                    },
                },
                // Semantic colors
                success: {
                    50: '#f0fdf4',
                    500: '#10b981',
                    600: '#059669',
                },
                warning: {
                    50: '#fffbeb',
                    500: '#f59e0b',
                    600: '#d97706',
                },
                error: {
                    50: '#fef2f2',
                    500: '#ef4444',
                    600: '#dc2626',
                },
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'soft-lg': '0 10px 40px -10px rgba(0, 0, 0, 0.1), 0 20px 50px -5px rgba(0, 0, 0, 0.06)',
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
        },
    },

    plugins: [forms],
};

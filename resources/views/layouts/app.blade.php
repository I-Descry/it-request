<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Global "Soft, Alive, Responsive" Styles */
            button:not(.action-btn):not(.pill-add), 
            .t-btn-submit, 
            .t-btn-cancel,
            a[style*="background-color: #f59e0b"] {
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
            
            button:not(.action-btn):not(.pill-add):hover:not(:disabled), 
            .t-btn-submit:hover, 
            .t-btn-cancel:hover,
            a[style*="background-color: #f59e0b"]:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                filter: brightness(1.08);
            }
            
            button:not(.action-btn):not(.pill-add):active:not(:disabled), 
            .t-btn-submit:active, 
            .t-btn-cancel:active,
            a[style*="background-color: #f59e0b"]:active {
                transform: translateY(1px);
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                filter: brightness(0.95);
            }

            input, select, textarea {
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            input:focus, select:focus, textarea:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
                outline: none !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

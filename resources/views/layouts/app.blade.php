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

        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
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

        <!-- Global Truncated Text Hover Scroll (Marquee) Effect -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.body.addEventListener('mouseover', function(e) {
                    let target = e.target;
                    let el = null;
                    
                    // Traverse up a few levels to find if any parent under the cursor has ellipsis
                    let attempts = 0;
                    while (target && target !== document.body && attempts < 4) {
                        if (target.nodeType === 1) {
                            const style = window.getComputedStyle(target);
                            if (style.textOverflow === 'ellipsis' && style.whiteSpace === 'nowrap' && style.overflow === 'hidden') {
                                el = target;
                                break;
                            }
                        }
                        target = target.parentElement;
                        attempts++;
                    }

                    if (el && !el.dataset.hoverMarquee) {
                        if (el.scrollWidth > el.clientWidth) {
                            el.dataset.hoverMarquee = "true";
                            el.style.textOverflow = 'clip';
                            
                            // Calculate how far it needs to move
                            const distance = el.scrollWidth - el.clientWidth + 15;
                            const duration = Math.max(1200, distance * 20); // Scale speed based on length
                            
                            // Cancel any existing animation
                            if (el._marqueeAnim) el._marqueeAnim.cancel();
                            
                            // Smoothly animate text-indent to slide the text to the left
                            el._marqueeAnim = el.animate([
                                { textIndent: '0px' },
                                { textIndent: `-${distance}px` }
                            ], {
                                duration: duration,
                                delay: 300, // Small delay before it starts sliding
                                fill: 'forwards',
                                easing: 'ease-in-out'
                            });
                        }
                    }
                });

                document.body.addEventListener('mouseout', function(e) {
                    // We need to check if the mouse is actually leaving the element or just moving to a child
                    let target = e.target;
                    let el = null;
                    
                    let attempts = 0;
                    while (target && target !== document.body && attempts < 4) {
                        if (target.dataset && target.dataset.hoverMarquee) {
                            el = target;
                            break;
                        }
                        target = target.parentElement;
                        attempts++;
                    }

                    // If we found a marquee element, check if the mouse actually left it
                    if (el && e.relatedTarget) {
                        if (!el.contains(e.relatedTarget)) {
                            if (el._marqueeAnim) {
                                el._marqueeAnim.cancel();
                            }
                            el.style.textIndent = '0px';
                            el.style.textOverflow = 'ellipsis';
                            delete el.dataset.hoverMarquee;
                        }
                    }
                });
            });
        </script>
    </body>
</html>

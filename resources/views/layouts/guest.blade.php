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

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        primaryLight: '#A855F7'
                    }
                }
            }
        }
    </script>

    <!-- Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        
        <div>
            <a href="/" class="flex items-center gap-2">

                <svg width="180" height="48" viewBox="0 0 520 140" xmlns="http://www.w3.org/2000/svg">

                    <!-- Gradient -->
                    <defs>
                        <linearGradient id="viewsysGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#A855F7" />
                            <stop offset="100%" stop-color="#4F46E5" />
                        </linearGradient>
                    </defs>

                    <!-- Icon Background -->
                    <rect x="10" y="10" width="120" height="120" rx="28" fill="url(#viewsysGrad)" />

                    <!-- Eye -->
                    <path d="M40 70 C55 45, 85 45, 100 70 C85 95, 55 95, 40 70 Z"
                        fill="none"
                        stroke="white"
                        stroke-width="6"
                        stroke-linecap="round"
                        stroke-linejoin="round" />

                    <!-- Dot -->
                    <circle cx="70" cy="70" r="7" fill="white" />

                    <!-- V -->
                    <path d="M50 50 L70 95 L90 50"
                        stroke="white"
                        stroke-width="6"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round" />

                    <!-- Text -->
                    <text x="170" y="85"
                        font-family="Inter, Arial, sans-serif"
                        font-size="64"
                        font-weight="700"
                        fill="#1F2937">
                        VIEW
                    </text>

                    <text x="340" y="85"
                        font-family="Inter, Arial, sans-serif"
                        font-size="64"
                        font-weight="700"
                        fill="#4F46E5">
                        SYS
                    </text>

                </svg>

            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>

    </div>
</body>
</html>
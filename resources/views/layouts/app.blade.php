<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Digital Signage') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#0f172a',
                        primary: '#4f46e5'
                    }
                }
            }
        }
    </script>

    <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body class="bg-gray-100 font-sans">

    @php
        function navActive($path)
        {
            return request()->is($path) ? 'bg-gray-800 text-white' : 'hover:bg-gray-800 hover:text-white';
        }
    @endphp

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-72 bg-sidebar text-gray-300 flex flex-col">

            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-gray-800">
                <span class="text-white text-lg font-semibold">
                    Digital Signage
                </span>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

                <a href="/dashboard" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('dashboard') }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Dashboard
                </a>

                @auth

                    @if(Auth::user()->role === 'superadmin')

                        <a href="/companies" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('companies*') }}">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                            Companies
                        </a>

                        <a href="/plans" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('plans*') }}">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            Subscriptions
                        </a>

                        <a href="/storage-usage"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('storage-usage*') }}">
                            <i data-lucide="database" class="w-5 h-5"></i>
                            Storage
                        </a>

                        <a href="/logs" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('logs*') }}">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            System Logs
                        </a>

                    @endif


                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')

                        <a href="/users" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('users*') }}">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            Users
                        </a>

                        <a href="/screens" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('screens*') }}">
                            <i data-lucide="monitor" class="w-5 h-5"></i>
                            Screens
                        </a>

                        <a href="/clusters" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('clusters*') }}">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                            Screen Clusters
                        </a>

                        <a href="/media" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('media*') }}">
                            <i data-lucide="image" class="w-5 h-5"></i>
                            Media Library
                        </a>

                        <a href="/playlists" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('playlists*') }}">
                            <i data-lucide="list-video" class="w-5 h-5"></i>
                            Playlists
                        </a>

                        <a href="/schedules" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('schedules*') }}">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                            Schedules
                        </a>

                        <a href="/devices" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('devices*') }}">
                            <i data-lucide="cpu" class="w-5 h-5"></i>
                            Devices
                        </a>

                        <a href="/analytics" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('analytics*') }}">
                            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                            Analytics
                        </a>

                    @endif


                    @if(Auth::user()->role === 'manager')

                        <a href="/screens/my"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('screens/my*') }}">
                            <i data-lucide="monitor" class="w-5 h-5"></i>
                            My Screens
                        </a>

                        <a href="/clusters" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('clusters*') }}">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                            Screen Clusters
                        </a>

                        <a href="/media" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('media*') }}">
                            <i data-lucide="image" class="w-5 h-5"></i>
                            Upload Media
                        </a>

                        <a href="/schedule" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('schedule*') }}">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                            Schedule
                        </a>

                    @endif

                @endauth

                <a href="/notifications"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('notifications*') }}">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    Notifications
                </a>

                <a href="/settings" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ navActive('settings*') }}">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    Settings
                </a>

            </nav>


            <!-- Sidebar User -->
            @auth
                <div class="border-t border-gray-800 p-4 flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <img class="w-9 h-9 rounded-full" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}">

                        <div class="text-sm">
                            <div class="text-white">{{ Auth::user()->name }}</div>
                            <div class="text-gray-400 text-xs capitalize">
                                {{ Auth::user()->role }}
                            </div>
                        </div>

                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-400 hover:text-red-300 text-sm">
                            Logout
                        </button>
                    </form>

                </div>
            @endauth

        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col"> <!-- Topbar -->
            <header class="bg-white border-b h-16 flex items-center justify-between px-8">
                <h1 class="font-semibold text-gray-800"> @yield('header', 'Dashboard') </h1>
                <div class="flex items-center gap-6"> <button class="relative text-gray-600 hover:text-gray-800"> <i
                            data-lucide="bell" class="w-5 h-5"></i> <span
                            class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span> </button> @auth
                                <div class="relative">
                                    <details>
                                        <summary class="list-none cursor-pointer flex items-center gap-3"> <img
                                                class="w-9 h-9 rounded-full"
                                                src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"> <span
                                                class="text-sm font-medium text-gray-700"> {{ Auth::user()->name }} </span>
                                        </summary>
                                        <div
                                            class="absolute right-0 mt-3 w-48 bg-white border rounded-lg shadow-lg overflow-hidden">
                                            <a href="/profile" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                                                <i data-lucide="user" class="w-4 h-4"></i> Profile </a>
                                            <form method="POST" action="{{ route('logout') }}"> @csrf <button type="submit"
                                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100"> <i
                                                        data-lucide="log-out" class="w-4 h-4"></i> Logout </button> </form>
                                        </div>
                                    </details>
                            </div> @endauth </div>
            </header> <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-8"> @yield('content') </main>
        </div>
    </div>
    <script> lucide.createIcons(); </script>
</body>

</html>Ï
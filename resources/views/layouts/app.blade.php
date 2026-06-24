<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheLocads Cloud CMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        a:hover {
            transform: translateX(2px);
        }

        button:hover {
            transform: scale(1.03);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-100 via-white to-slate-200 antialiased">

    @php
        function navActive($path)
        {
            return request()->is($path);
        }
    @endphp

    <div class="flex min-h-screen relative overflow-x-hidden">
        
        <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/40 z-40 hidden lg:hidden transition-opacity duration-300"></div>

        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-[270px] bg-white border-r border-gray-100 shadow-sm flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="h-[70px] px-6 flex items-center justify-between border-b">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow"> 
                        <i data-lucide="monitor-play" class="w-5 h-5"></i> 
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800 leading-tight"> TheLocads CMS </h2>
                        <p class="text-xs text-gray-400"> Signage Platform </p>
                    </div>
                </div>
                <button id="closeSidebarBtn" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg lg:hidden">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div> 
            
            <nav class="flex-1 overflow-y-auto px-4 py-5 text-sm text-gray-600 space-y-6">
                @php $active = 'bg-indigo-50 text-indigo-600 font-medium';
                $inactive = 'hover:bg-gray-50'; @endphp
                <a href="/dashboard"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('dashboard') ? $active : $inactive }}">
                    <i data-lucide="layout-dashboard" class="w-4"></i> Dashboard </a> @role('superadmin|admin')
                <div>
                    <p class="text-xs text-gray-400 px-3 mb-2 uppercase tracking-wide"> Management </p> <a href="/users"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('users*') ? $active : $inactive }}">
                        <i data-lucide="users" class="w-4"></i> Users </a>
                </div> @endrole @role('superadmin')
                <div>
                    <p class="text-xs text-gray-400 px-3 mb-2 uppercase tracking-wide"> System </p> <a href="/companies"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('companies*') ? $active : $inactive }}">
                        <i data-lucide="building-2" class="w-4"></i> Companies </a> <a href="/plans"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('plans*') ? $active : $inactive }}">
                        <i data-lucide="credit-card" class="w-4"></i> Subscriptions </a>
                </div> @endrole @role('superadmin|admin')
                <div>
                    <p class="text-xs text-gray-400 px-3 mb-2 uppercase tracking-wide"> Screens </p> <a href="/screens"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('screens*') ? $active : $inactive }}">
                        <i data-lucide="tv" class="w-4"></i> Screens </a> <a href="/clusters"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('clusters*') ? $active : $inactive }}">
                        <i data-lucide="layers" class="w-4"></i> Clusters </a>
                </div> @endrole <div>
                    <p class="text-xs text-gray-400 px-3 mb-2 uppercase tracking-wide"> Content </p> <a href="/media"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('media*') ? $active : $inactive }}">
                        <i data-lucide="image" class="w-4"></i> Media </a> <a href="/playlists"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('playlists*') ? $active : $inactive }}">
                        <i data-lucide="list-video" class="w-4"></i> Playlists </a> <a href="/schedules"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ navActive('schedules*') ? $active : $inactive }}">
                        <i data-lucide="calendar" class="w-4"></i> Schedules </a>
                </div> <div>
                    <p class="text-xs text-gray-400 px-3 mb-2 uppercase tracking-wide"> Other </p> <a
                        href="/notifications" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $inactive }}"> <i
                            data-lucide="bell" class="w-4"></i> Notifications </a> <a href="/settings"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $inactive }}"> <i data-lucide="settings"
                            class="w-4"></i> Settings </a>
                </div>
            </nav> <div class="p-4 border-t text-xs text-gray-400 text-center"> © {{ date('Y') }} TheLocads </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 lg:ml-[270px]">

            <header class="h-16 bg-white/80 backdrop-blur border-b flex justify-between items-center px-4 sm:px-6 gap-4">

                <div class="flex items-center gap-3 flex-1 max-w-xl">
                    <button id="openSidebarBtn" class="p-2 text-gray-600 hover:bg-gray-100 rounded-xl lg:hidden focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>

                    <div class="relative w-full">
                        <i class="fa fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input class="w-full h-11 pl-11 pr-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                            placeholder="Search...">
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">

                    <button class="relative p-2 rounded-xl hover:bg-gray-100 text-gray-600">
                        <i class="fa fa-bell"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="relative">

                        <button id="userMenuBtn"
                            class="w-9 h-9 bg-indigo-500 text-white flex items-center justify-center rounded-full font-medium shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>

                        <div id="userDropdown"
                            class="hidden absolute right-0 mt-2 w-44 bg-white border rounded-xl shadow-lg z-50">

                            <div class="px-3 py-2 text-xs text-gray-500 border-b truncate">
                                {{ Auth::user()->name }}
                            </div>

                            <a href="{{ route('users.show', Auth::user()) }}"
                                class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Logout
                                </button>
                            </form>

                        </div>

                    </div>

                </div>

            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            lucide.createIcons();

            // RESPONSIVE SIDEBAR LOGIC
            const sidebar = document.getElementById('sidebar');
            const openSidebarBtn = document.getElementById('openSidebarBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarBackdrop.classList.toggle('hidden');
            }

            if(openSidebarBtn) openSidebarBtn.addEventListener('click', toggleSidebar);
            if(closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
            if(sidebarBackdrop) sidebarBackdrop.addEventListener('click', toggleSidebar);


            // ORIGINAL SUBMENU LOGIC
            document.querySelectorAll('.menu-toggle').forEach(btn => {
                btn.addEventListener('click', function () {

                    const parent = this.closest('.menu');
                    const submenu = parent.querySelector('.submenu');
                    const icon = this.querySelector('.fa-chevron-right');

                    document.querySelectorAll('.submenu').forEach(el => {
                        if (el !== submenu) el.classList.add('hidden');
                    });

                    document.querySelectorAll('.fa-chevron-right').forEach(i => {
                        if (i !== icon) i.classList.remove('rotate-90');
                    });

                    submenu.classList.toggle('hidden');
                    icon.classList.toggle('rotate-90');
                });
            });

            // USER DROPDOWN LOGIC
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');

            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });

                window.addEventListener('click', function (e) {
                    if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }

        });
    </script>

</body>

</html>
<x-guest-layout>

<div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-2">

    <!-- LEFT SIDE -->
    <div class="hidden lg:flex relative flex-col justify-center px-20
        bg-gradient-to-br from-indigo-800 via-purple-800 to-indigo-900 text-white overflow-hidden">

        <!-- Glow Effects -->
        <div class="absolute w-96 h-96 bg-purple-500 opacity-20 rounded-full blur-3xl top-10 left-10"></div>
        <div class="absolute w-72 h-72 bg-indigo-400 opacity-20 rounded-full blur-3xl bottom-10 right-10"></div>

        <div class="relative max-w-lg z-10">

            <h1 class="text-5xl font-bold leading-tight tracking-tight">
                Smart Digital
                <span class="text-indigo-300">Signage</span><br>
                Management
            </h1>

            <p class="mt-6 text-lg text-indigo-200 leading-relaxed">
                Control, schedule and monitor all your screens from one intelligent platform built for modern businesses.
            </p>

          <!-- FEATURES -->
<div class="mt-12 space-y-5">

<!-- ITEM -->
<div class="group flex items-center gap-4 px-5 py-4 rounded-2xl 
    bg-white/10 backdrop-blur-md border border-white/10
    hover:bg-white/20 hover:scale-[1.02] transition-all duration-300">

    <!-- ICON -->
    <div class="w-10 h-10 flex items-center justify-center rounded-xl 
        bg-gradient-to-br from-green-400/20 to-green-600/20 
        text-green-300 text-lg shadow-inner">
        ✓
    </div>

    <!-- TEXT -->
    <div>
        <p class="text-white font-semibold text-sm tracking-wide">
            Real-time Sync
        </p>
        <p class="text-indigo-200 text-xs">
            Instantly update content across all screens
        </p>
    </div>

</div>

<!-- ITEM -->
<div class="group flex items-center gap-4 px-5 py-4 rounded-2xl 
    bg-white/10 backdrop-blur-md border border-white/10
    hover:bg-white/20 hover:scale-[1.02] transition-all duration-300">

    <!-- ICON -->
    <div class="w-10 h-10 flex items-center justify-center rounded-xl 
        bg-gradient-to-br from-blue-400/20 to-blue-600/20 
        text-blue-300 text-lg shadow-inner">
        ⏱
    </div>

    <!-- TEXT -->
    <div>
        <p class="text-white font-semibold text-sm tracking-wide">
            Smart Scheduling
        </p>
        <p class="text-indigo-200 text-xs">
            Automate content delivery with precision timing
        </p>
    </div>

</div>

<!-- ITEM -->
<div class="group flex items-center gap-4 px-5 py-4 rounded-2xl 
    bg-white/10 backdrop-blur-md border border-white/10
    hover:bg-white/20 hover:scale-[1.02] transition-all duration-300">

    <!-- ICON -->
    <div class="w-10 h-10 flex items-center justify-center rounded-xl 
        bg-gradient-to-br from-purple-400/20 to-purple-600/20 
        text-purple-300 text-lg shadow-inner">
        ☁
    </div>

    <!-- TEXT -->
    <div>
        <p class="text-white font-semibold text-sm tracking-wide">
            Cloud Media Control
        </p>
        <p class="text-indigo-200 text-xs">
            Manage and distribute media from anywhere
        </p>
    </div>

</div>

</div>

        </div>

        <!-- FOOTER -->
        <div class="absolute bottom-6 left-20 text-xs text-indigo-300 z-10">
            © {{ date('Y') }} Thelocads — All rights reserved
        </div>

    </div>


    <!-- RIGHT SIDE -->
    <div class="flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-12 py-10">

        <div class="w-full max-w-md sm:max-w-lg">

 <!-- LOGO + COMPANY -->
<div class="text-center mb-8">

<!-- LOGO IMAGE -->
<div class="flex justify-center">
    <img src="{{ asset('logo.png') }}" 
         alt="TheLocads Logo"
         class="h-16 w-auto object-contain">
</div>



</div>

            <!-- CARD -->
            <div class="relative bg-white/80 backdrop-blur-xl border border-gray-200
                rounded-3xl shadow-xl p-8 sm:p-10 transition-all duration-300 hover:shadow-2xl">

                <!-- TITLE -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
                        Welcome Back
                    </h2>
                    <p class="text-gray-500 mt-2 text-sm">
                        Login to your dashboard
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <label class="text-sm font-semibold text-gray-600">
                            Email Address
                        </label>

                        <div class="relative mt-2">
                            <input type="email" name="email"
                                placeholder="admin@thelocads.com"
                                class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-300 bg-white
                                focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                outline-none transition-all duration-200">

                            <span class="absolute left-3 top-3.5 text-gray-400 text-sm">📧</span>
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="text-sm font-semibold text-gray-600">
                            Password
                        </label>

                        <div class="relative mt-2">
                            <input id="password" type="password" name="password"
                                placeholder="Enter your password"
                                class="w-full px-4 py-3 pl-11 pr-11 rounded-xl border border-gray-300 bg-white
                                focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                outline-none transition-all duration-200">

                            <span class="absolute left-3 top-3.5 text-gray-400 text-sm">🔒</span>

                            <button type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                                👁
                            </button>
                        </div>
                    </div>

                    <!-- OPTIONS -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Remember me
                        </label>

                        <a href="#"
                           class="text-indigo-600 font-medium hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <!-- BUTTON -->
                    <button
                        class="w-full py-3 rounded-xl text-white font-semibold text-sm tracking-wide
                        bg-gradient-to-r from-indigo-600 to-purple-600
                        hover:from-indigo-700 hover:to-purple-700
                        shadow-lg hover:shadow-xl
                        transition-all duration-300">
                        Sign In →
                    </button>

                </form>

                <!-- FOOTER -->
                <p class="text-center text-xs text-gray-400 mt-6">
                    Secure login powered by Thelocads
                </p>

            </div>

        </div>

    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</x-guest-layout>
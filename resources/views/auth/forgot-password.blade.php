<x-guest-layout>

<div class="w-full">

    <!-- TITLE -->
    <div class="mb-8 text-center">

        <h2 class="text-3xl font-bold text-gray-800">
            Reset Your Password
        </h2>

        <p class="text-gray-500 mt-2">
            Enter your email and we’ll send you a link to reset your ViewSys account password.
        </p>

    </div>


    <!-- STATUS -->
    <x-auth-session-status class="mb-6" :status="session('status')" />


    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf


        <!-- EMAIL -->
        <div>

            <label class="text-sm font-medium text-gray-700">
                Work Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="admin@company.com"
                class="w-full mt-2 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            >

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>


        <!-- BUTTON -->
        <button
            class="w-full py-3 rounded-xl text-white font-semibold
            bg-gradient-to-r from-purple-600 to-indigo-600
            hover:from-purple-700 hover:to-indigo-700
            transition shadow-md"
        >
            Send Password Reset Link
        </button>

    </form>


    <!-- FOOTER -->
    <div class="mt-8 text-center text-sm text-gray-400">
        ViewSys Digital Signage Platform
    </div>

</div>

</x-guest-layout>
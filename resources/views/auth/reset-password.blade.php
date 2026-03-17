<x-guest-layout>

<div class="w-full">

    <!-- HEADER -->
    <div class="text-center mb-8">

        <h2 class="text-3xl font-bold text-gray-800">
            Create a New Password
        </h2>

        <p class="text-gray-500 mt-2">
            Set a new password to regain access to your ViewSys dashboard.
        </p>

    </div>


    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- TOKEN -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">


        <!-- EMAIL -->
        <div>

            <label class="text-sm font-medium text-gray-700">
                Work Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                class="w-full mt-2 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            >

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>


        <!-- PASSWORD -->
        <div>

            <label class="text-sm font-medium text-gray-700">
                New Password
            </label>

            <div class="relative mt-2">

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Create a new password"
                    class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                >

                <button
                    type="button"
                    onclick="togglePassword('password','eye1','eye1off')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >

                    <!-- OPEN -->
                    <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0"/>

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                        c4.477 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.065 7-9.542 7
                        -4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>

                    <!-- CLOSED -->
                    <svg id="eye1off"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 hidden"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                        <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19
                        c-4.478 0-8.268-2.943-9.542-7
                        a9.956 9.956 0 012.042-3.368M6.223 6.223
                        A9.956 9.956 0 0112 5c4.478 0 8.268
                        2.943 9.542 7a9.973 9.973 0 01-4.132
                        5.411M15 12a3 3 0 00-3-3m0 0a3
                        3 0 00-3 3m3-3v6m9 3L3 3"/>
                    </svg>

                </button>

            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>


        <!-- CONFIRM PASSWORD -->
        <div>

            <label class="text-sm font-medium text-gray-700">
                Confirm Password
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm your password"
                class="w-full mt-2 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
            >

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

        </div>


        <!-- BUTTON -->
        <button
            class="w-full py-3 rounded-xl text-white font-semibold
            bg-gradient-to-r from-purple-600 to-indigo-600
            hover:from-purple-700 hover:to-indigo-700
            transition shadow-md"
        >
            Reset Password
        </button>


        <!-- BACK TO LOGIN -->
        <div class="text-center text-sm text-gray-500">

            Remember your password?

            <a href="{{ route('login') }}"
               class="text-indigo-600 font-medium hover:underline">
               Sign in
            </a>

        </div>

    </form>


    <!-- FOOTER -->
    <div class="mt-8 text-center text-sm text-gray-400">
        © {{ date('Y') }} ViewSys Digital Signage Platform
    </div>

</div>


<script>

function togglePassword(inputId, eyeOpenId, eyeCloseId) {

    const input = document.getElementById(inputId);
    const open = document.getElementById(eyeOpenId);
    const close = document.getElementById(eyeCloseId);

    if (input.type === "password") {

        input.type = "text";
        open.classList.add("hidden");
        close.classList.remove("hidden");

    } else {

        input.type = "password";
        open.classList.remove("hidden");
        close.classList.add("hidden");

    }

}

</script>

</x-guest-layout>
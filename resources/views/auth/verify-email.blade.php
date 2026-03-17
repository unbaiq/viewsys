<x-guest-layout>

<div class="w-full text-center">

    <!-- HEADER -->
    <div class="mb-6">

        <h2 class="text-3xl font-bold text-gray-800">
            Verify Your Email
        </h2>

        <p class="text-gray-500 mt-2 max-w-sm mx-auto">
            To activate your ViewSys account, please confirm your email address using the link we sent you.
        </p>

    </div>


    <!-- SUCCESS MESSAGE -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-3 rounded-lg bg-green-50 text-green-600 text-sm font-medium">
            A new verification link has been sent to your email address.
        </div>
    @endif


    <!-- ACTIONS -->
    <div class="space-y-4">

        <!-- RESEND -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button
                class="w-full py-3 rounded-xl text-white font-semibold
                bg-gradient-to-r from-purple-600 to-indigo-600
                hover:from-purple-700 hover:to-indigo-700
                transition shadow-md"
            >
                Resend Verification Email
            </button>

        </form>


        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="text-sm text-gray-500 hover:text-gray-700 underline"
            >
                Log out of this account
            </button>

        </form>

    </div>


    <!-- FOOTER -->
    <div class="mt-8 text-sm text-gray-400">
        ViewSys Digital Signage Platform
    </div>

</div>

</x-guest-layout>
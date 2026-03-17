@extends('layouts.app')

@section('header', 'Plan Details')

@section('content')

    <div class="min-h-[70vh] flex items-center justify-center">

        <div class="w-full max-w-3xl bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

            <!-- HEADER -->
            <div class="flex items-start justify-between mb-6">

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $plan->name }} Plan
                    </h2>

                    <p class="text-sm text-gray-500">
                        Subscription plan configuration and limits
                    </p>
                </div>

                @if($plan->is_active)

                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                        Active
                    </span>

                @else

                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-full">
                        Disabled
                    </span>

                @endif

            </div>


            <!-- PLAN STATS -->
            <div class="grid grid-cols-3 gap-6 mb-8">

                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-xs text-gray-500">
                        Price
                    </div>

                    <div class="text-xl font-semibold text-gray-800 mt-1">
                        ₹{{ number_format($plan->price) }}
                    </div>

                    <div class="text-xs text-gray-400">
                        per month
                    </div>

                </div>


                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-xs text-gray-500">
                        Screens Allowed
                    </div>

                    <div class="text-xl font-semibold text-gray-800 mt-1">
                        {{ $plan->screen_limit }}
                    </div>

                </div>


                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-xs text-gray-500">
                        Storage
                    </div>

                    <div class="text-xl font-semibold text-gray-800 mt-1">
                        {{ $plan->storage_limit }} MB
                    </div>

                </div>

            </div>


            <!-- FEATURES -->
            <div class="mb-8">

                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Features
                </h3>

                <div class="grid grid-cols-2 gap-3">

                    @forelse($plan->features ?? [] as $feature)

                        <div class="flex items-center gap-2 text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />

                            </svg>

                            {{ $feature }}

                        </div>

                    @empty

                        <div class="text-sm text-gray-500">
                            No features listed.
                        </div>

                    @endforelse

                </div>

            </div>


            <!-- ACTIONS -->
            <div class="flex items-center justify-between border-t pt-6">

                <a href="{{ route('plans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Back to Plans
                </a>

                <div class="flex gap-3">

                    <a href="{{ route('plans.edit', $plan) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2 rounded-lg shadow-sm">
                        Edit Plan
                    </a>

                    <form method="POST" action="{{ route('plans.destroy', $plan) }}"
                        onsubmit="return confirm('Delete this plan?')">

                        @csrf
                        @method('DELETE')

                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm px-5 py-2 rounded-lg">
                            Delete
                        </button>

                    </form>

                </div>

            </div>


        </div>

    </div>

@endsection
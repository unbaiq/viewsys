@extends('layouts.app')

@section('header', 'Plan Details')

@section('content')

<div class="max-w-8xl mx-auto space-y-5">

<!-- ================= HEADER ================= -->
<div class="bg-white border border-slate-200 rounded-xl px-5 py-4 flex items-center justify-between">

    <div>
        <div class="text-base font-semibold text-slate-900">
            {{ $plan->name }} Plan
        </div>
        <div class="text-xs text-slate-500">
            Subscription configuration
        </div>
    </div>

    <!-- STATUS -->
    @if($plan->is_active)
        <span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full font-medium">
            Active
        </span>
    @else
        <span class="px-2 py-0.5 text-xs bg-red-100 text-red-600 rounded-full font-medium">
            Disabled
        </span>
    @endif

</div>


<!-- ================= STATS ================= -->
<div class="grid grid-cols-3 gap-4">

    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3">
        <div class="text-[11px] text-slate-500">Price</div>
        <div class="text-lg font-semibold text-slate-900">
            ₹{{ number_format($plan->price) }}
        </div>
        <div class="text-[11px] text-slate-400">per month</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3">
        <div class="text-[11px] text-slate-500">Screens</div>
        <div class="text-lg font-semibold text-slate-900">
            {{ $plan->screen_limit }}
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3">
        <div class="text-[11px] text-slate-500">Storage</div>
        <div class="text-lg font-semibold text-slate-900">
            {{ $plan->storage_limit }} MB
        </div>
    </div>

</div>


<!-- ================= FEATURES ================= -->
<div class="bg-white border border-slate-200 rounded-xl">

    <div class="px-5 py-3 border-b text-sm font-medium text-slate-700">
        Features
    </div>

    <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-3 text-xs">

        @forelse($plan->features ?? [] as $feature)

            <div class="flex items-center gap-2 text-slate-700 bg-slate-50 rounded px-3 py-2">

                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>

                {{ $feature }}

            </div>

        @empty

            <div class="text-slate-400">
                No features listed.
            </div>

        @endforelse

    </div>

</div>


<!-- ================= ACTIONS ================= -->
<div class="flex items-center justify-between">

    <a href="{{ route('plans.index') }}"
       class="text-xs text-slate-500 hover:text-slate-700">
        ← Back
    </a>

    <div class="flex gap-2">

        <a href="{{ route('plans.edit', $plan) }}"
           class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Edit
        </a>

        <form method="POST" action="{{ route('plans.destroy', $plan) }}"
              onsubmit="return confirm('Delete this plan?')">
            @csrf
            @method('DELETE')

            <button class="px-4 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700">
                Delete
            </button>
        </form>

    </div>

</div>

</div>

@endsection
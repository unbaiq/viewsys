@extends('layouts.app')

@section('header','Screen Details')

@section('content')

<div class="max-w-8xl mx-auto space-y-5">

<!-- ================= HEADER ================= -->
<div class="bg-white border border-slate-200 rounded-xl px-5 py-4 flex items-center justify-between">

    <div>
        <div class="text-base font-semibold text-slate-900">
            {{ $screen->name }}
        </div>
        <div class="text-xs text-slate-500">
            Device ID: {{ $screen->device_id ?? '-' }}
        </div>
    </div>

    <!-- STATUS -->
    @if($screen->isOnline())
        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full font-medium">
            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
            Online
        </span>
    @else
        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-red-100 text-red-600 rounded-full font-medium">
            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
            Offline
        </span>
    @endif

</div>


<!-- ================= DETAILS ================= -->
<div class="bg-white border border-slate-200 rounded-xl">

<div class="px-5 py-3 border-b text-sm font-medium text-slate-700">
    Screen Information
</div>

<div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">

    <div>
        <p class="text-xs text-slate-500">Company</p>
        <p class="font-medium text-slate-800">
            {{ $screen->company->name ?? '-' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Location</p>
        <p class="font-medium text-slate-800">
            {{ $screen->location ?? '-' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Orientation</p>
        <p class="font-medium text-slate-800">
            {{ $screen->orientation ? ucfirst($screen->orientation) : '-' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Status</p>
        <p class="font-medium {{ $screen->isOnline() ? 'text-green-600' : 'text-red-600' }}">
            {{ $screen->isOnline() ? 'Online' : 'Offline' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Created</p>
        <p class="font-medium text-slate-800">
            {{ optional($screen->created_at)->format('d M Y') ?? '-' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Last Seen</p>
        <p class="font-medium text-slate-800">
            {{ $screen->last_seen ? $screen->last_seen->diffForHumans() : '-' }}
        </p>
    </div>

</div>

</div>


<!-- ================= ACTIONS ================= -->
<div class="flex items-center justify-between">

    <a href="{{ route('screens.index') }}"
       class="text-xs text-slate-500 hover:text-slate-700">
        ← Back
    </a>

    <div class="flex gap-2">

        <a href="{{ route('screens.edit',$screen) }}"
           class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Edit
        </a>

        <form method="POST"
              action="{{ route('screens.destroy',$screen) }}"
              onsubmit="return confirm('Delete this screen?')">
            @csrf
            @method('DELETE')

            <button
                class="px-4 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700">
                Delete
            </button>

        </form>

    </div>

</div>

</div>

@endsection
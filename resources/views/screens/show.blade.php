@extends('layouts.app')

@section('header','Screen Details')

@section('content')

<div class="max-w-3xl mx-auto">

<div class="bg-white shadow-sm border border-gray-100 rounded-2xl">

    <!-- Header -->
    <div class="p-6 border-b flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $screen->name }}
            </h2>

            <p class="text-sm text-gray-500">
                Device ID: {{ $screen->device_id }}
            </p>
        </div>

        <a href="{{ route('screens.index') }}"
           class="text-sm text-gray-600 hover:text-black">
            ← Back
        </a>

    </div>


    <!-- Details -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

        <div>
            <p class="text-gray-500 mb-1">Company</p>
            <p class="font-medium text-gray-800">
                {{ $screen->company->name ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 mb-1">Location</p>
            <p class="font-medium text-gray-800">
                {{ $screen->location ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 mb-1">Orientation</p>
            <p class="font-medium text-gray-800">
                {{ $screen->orientation ? ucfirst($screen->orientation) : '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 mb-1">Status</p>

            @if($screen->isOnline())
                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                    Online
                </span>
            @else
                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
                    Offline
                </span>
            @endif

        </div>

        <div>
            <p class="text-gray-500 mb-1">Created</p>
            <p class="font-medium text-gray-800">
                {{ optional($screen->created_at)->format('d M Y, h:i A') ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-gray-500 mb-1">Last Seen</p>
            <p class="font-medium text-gray-800">
                {{ $screen->last_seen ? $screen->last_seen->diffForHumans() : '-' }}
            </p>
        </div>

    </div>


    <!-- Footer Actions -->
    <div class="p-6 border-t flex gap-3">

        <a href="{{ route('screens.edit',$screen) }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
            Edit Screen
        </a>

        <form method="POST"
              action="{{ route('screens.destroy',$screen) }}"
              onsubmit="return confirm('Delete this screen?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                Delete
            </button>

        </form>

    </div>

</div>

</div>

@endsection
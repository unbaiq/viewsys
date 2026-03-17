@extends('layouts.app')

@section('header','Create Schedule')

@section('content')

<div class="max-w-3xl mx-auto p-6">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <!-- Header -->
        <div class="px-6 py-5 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Create Schedule</h2>
            <p class="text-sm text-gray-500">Assign playlist timing to a screen</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('schedules.store') }}" class="p-6 space-y-6">
        @csrf

        <!-- Screen -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Screen
            </label>

            <select name="screen_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:bg-white outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">

                @foreach($screens as $screen)
                    <option value="{{ $screen->id }}">{{ $screen->name }}</option>
                @endforeach

            </select>
        </div>

        <!-- Playlist -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Playlist
            </label>

            <select name="playlist_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:bg-white outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">

                @foreach($playlists as $playlist)
                    <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                @endforeach

            </select>
        </div>

        <!-- Date Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Start Date
                </label>

                <input type="date" name="start_date"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:bg-white outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    End Date
                </label>

                <input type="date" name="end_date"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:bg-white outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
            </div>

        </div>

        <!-- Time Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Start Time
                </label>

                <input type="time" name="start_time"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:bg-white outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    End Time
                </label>

                <input type="time" name="end_time"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:bg-white outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
            </div>

        </div>

        <!-- Days of Week -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Days of Week
            </label>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                @php
                $days = [
                    'mon' => 'Monday',
                    'tue' => 'Tuesday',
                    'wed' => 'Wednesday',
                    'thu' => 'Thursday',
                    'fri' => 'Friday',
                    'sat' => 'Saturday',
                    'sun' => 'Sunday'
                ];
                @endphp

                @foreach($days as $key => $day)

                <label class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-100">

                    <input type="checkbox"
                           name="days_of_week[]"
                           value="{{ $key }}"
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">

                    <span class="text-sm text-gray-700">
                        {{ $day }}
                    </span>

                </label>

                @endforeach

            </div>

            <p class="text-xs text-gray-400 mt-2">
                Select the days this schedule should run
            </p>

        </div>

        <!-- Priority -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Priority
            </label>

            <input type="number"
                   name="priority"
                   value="1"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:bg-white outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between pt-6 border-t">

            <a href="{{ route('schedules.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                Cancel
            </a>

            <button
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm font-medium shadow">
                Create Schedule
            </button>

        </div>

        </form>

    </div>

</div>

@endsection
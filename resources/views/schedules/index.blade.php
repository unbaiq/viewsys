@extends('layouts.app')

@section('header','Schedules')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    @if(session('success'))
        <div id="flash-alert" class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-check-circle mr-2"></i>
            <div>
                <span class="font-semibold">Success!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-alert" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-exclamation-circle mr-2"></i>
            <div>
                <span class="font-semibold">Alert!</span> {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">

        <div class="px-5 py-4 border-b flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Screen Schedules</h2>
                <p class="text-xs text-slate-500">Manage playlist assignments</p>
            </div>

            <a href="{{ route('schedules.create') }}"
               class="bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Schedule
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Target</th>
                        <th class="text-left">Playlist</th>
                        <th class="text-left">Schedule</th>
                        <th class="text-left">Days</th>
                        <th class="text-left">Priority</th>
                        <th class="text-right pr-5">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($schedules as $schedule)
                    <tr class="hover:bg-slate-50 transition">
                        
                        <td class="px-5 py-3">
                            @if($schedule->is_default)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-green-100 text-green-700 font-medium">
                                        All Screens
                                    </span>
                                </div>
                            @elseif($schedule->cluster)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-purple-100 text-purple-700 font-medium">
                                        Cluster
                                    </span>
                                    <span class="text-slate-700 font-medium">
                                        {{ $schedule->cluster->name }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-blue-100 text-blue-700 font-medium">
                                        Screen
                                    </span>
                                    <span class="text-slate-800 font-medium">
                                        {{ $schedule->screen->name ?? 'Deleted' }}
                                    </span>
                                </div>
                            @endif
                        </td>

                        <td class="text-slate-600">
                            {{ $schedule->playlist->name ?? 'Deleted' }}
                        </td>

                        <td class="text-slate-500">
                            @php
                                $isAlways = !$schedule->start_date && !$schedule->start_time && !$schedule->days_of_week;
                            @endphp

                            @if($isAlways)
                                <span class="px-2 py-0.5 text-[11px] rounded-full bg-green-100 text-green-700 font-medium">
                                    Always
                                </span>
                            @else
                                @if($schedule->start_date)
                                    <div>{{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}</div>
                                @endif
                                @if($schedule->start_time)
                                    <div class="text-[10px] text-slate-400">{{ $schedule->start_time }}</div>
                                @endif
                                @if($schedule->end_date)
                                    <div class="mt-1">{{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}</div>
                                @endif
                                @if($schedule->end_time)
                                    <div class="text-[10px] text-slate-400">{{ $schedule->end_time }}</div>
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($schedule->days_of_week)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($schedule->days_of_week as $day)
                                        <span class="px-2 py-0.5 text-[10px] bg-slate-100 rounded">
                                            {{ ucfirst($day) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 text-[11px]">All</span>
                            @endif
                        </td>

                        <td>
                            <span class="px-2 py-0.5 text-[11px] rounded bg-indigo-100 text-indigo-700 font-medium">
                                {{ $schedule->priority }}
                            </span>
                        </td>

                        <td class="text-right pr-5">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('schedules.edit',$schedule) }}"
                                   class="p-2 rounded hover:bg-indigo-50 text-indigo-600"
                                   title="Edit">
                                    <i class="fa fa-pen text-xs"></i>
                                </a>

                                @php
                                    // Identify clean name labels contextually for the popup
                                    $targetName = $schedule->is_default ? 'All Screens' : ($schedule->cluster ? $schedule->cluster->name : ($schedule->screen->name ?? 'Deleted Screen'));
                                    $playlistName = $schedule->playlist->name ?? 'Deleted Playlist';
                                    $displayText = "{$playlistName} linked to {$targetName}";
                                @endphp

                                <button type="button" 
                                        onclick="confirmScheduleDelete('{{ route('schedules.destroy', $schedule) }}', '{{ addslashes($displayText) }}')"
                                        class="p-2 rounded hover:bg-red-50 text-red-600"
                                        title="Delete">
                                    <i class="fa fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-14 text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <div class="text-2xl">📅</div>
                                <span>No schedules found</span>
                            </div>
                            <a href="{{ route('schedules.create') }}"
                               class="inline-block mt-3 bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                Create Schedule
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
            <div class="px-5 py-3 border-t bg-slate-50">
                {{ $schedules->links() }}
            </div>
        @endif

    </div>
</div>

<div id="deleteScheduleModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md p-5 transform transition-all space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Delete Schedule</h3>
                <p class="text-xs text-slate-500 mt-0.5">Are you sure you want to permanently delete the schedule tracking <span id="deleteScheduleDetails" class="font-medium text-slate-800"></span>?</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="closeScheduleDeleteModal()" class="px-3 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-medium transition">
                Cancel
            </button>
            <form id="deleteScheduleForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-xs font-medium transition">
                    Delete Schedule
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle Auto-fading Flash Messages
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById('flash-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        }
    });

    // Custom Modal Controls
    function confirmScheduleDelete(url, displayDetails) {
        document.getElementById('deleteScheduleDetails').innerText = displayDetails;
        document.getElementById('deleteScheduleForm').setAttribute('action', url);
        document.getElementById('deleteScheduleModal').classList.remove('hidden');
    }

    function closeScheduleDeleteModal() {
        document.getElementById('deleteScheduleModal').classList.add('hidden');
    }
</script>

@endsection
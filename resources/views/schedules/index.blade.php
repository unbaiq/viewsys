@extends('layouts.app')

@section('header','Schedules')

@section('content')

<div class="max-w-8xl mx-auto p-6">

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

<!-- Header -->
<div class="flex items-center justify-between px-6 py-5 border-b bg-gradient-to-r from-gray-50 to-white">
<div>
<h2 class="text-lg font-semibold text-gray-800">Screen Schedules</h2>
<p class="text-sm text-gray-500">Manage playlists assigned to screens</p>
</div>

<a href="{{ route('schedules.create') }}"
class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">

<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
viewBox="0 0 24 24" stroke="currentColor">
<path stroke-linecap="round" stroke-linejoin="round"
stroke-width="2" d="M12 4v16m8-8H4"/>
</svg>

Create Schedule
</a>
</div>

<!-- Table -->
<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
<tr>
<th class="px-6 py-4 text-left">Screen</th>
<th class="px-6 py-4 text-left">Playlist</th>
<th class="px-6 py-4 text-left">Start</th>
<th class="px-6 py-4 text-left">End</th>
<th class="px-6 py-4 text-left">Priority</th>
<th class="px-6 py-4 text-right">Actions</th>
</tr>
</thead>

<tbody class="divide-y">

@forelse($schedules as $schedule)

<tr class="hover:bg-gray-50 transition">

<!-- Screen -->
<td class="px-6 py-4 font-medium text-gray-800">
{{ $schedule->screen->name ?? 'Deleted Screen' }}
</td>

<!-- Playlist -->
<td class="px-6 py-4 text-gray-600">
{{ $schedule->playlist->name ?? 'Deleted Playlist' }}
</td>

<!-- Start -->
<td class="px-6 py-4 text-gray-500">
@if($schedule->start_date)
{{ $schedule->start_date->format('d M Y') }}
@endif

@if($schedule->start_time)
<span class="text-gray-400">
{{ $schedule->start_time }}
</span>
@endif
</td>

<!-- End -->
<td class="px-6 py-4 text-gray-500">
@if($schedule->end_date)
{{ $schedule->end_date->format('d M Y') }}
@endif

@if($schedule->end_time)
<span class="text-gray-400">
{{ $schedule->end_time }}
</span>
@endif
</td>

<!-- Priority -->
<td class="px-6 py-4">
<span class="px-3 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700 font-medium">
{{ $schedule->priority }}
</span>
</td>

<!-- Actions -->
<td class="px-6 py-4 text-right">

<a href="{{ route('schedules.edit',$schedule) }}"
class="text-indigo-600 hover:text-indigo-800 font-medium mr-4">
Edit
</a>

<form method="POST"
action="{{ route('schedules.destroy',$schedule) }}"
class="inline">
@csrf
@method('DELETE')

<button onclick="return confirm('Delete this schedule?')"
class="text-red-600 hover:text-red-800 font-medium">
Delete
</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="6" class="text-center py-14">

<div class="flex flex-col items-center">

<div class="bg-gray-100 p-4 rounded-full mb-3">
📅
</div>

<p class="text-gray-600 font-medium">
No schedules created
</p>

<p class="text-sm text-gray-400 mb-4">
Create your first screen schedule
</p>

<a href="{{ route('schedules.create') }}"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
Create Schedule
</a>

</div>

</td>
</tr>

@endforelse

</tbody>

</table>

</div>

<!-- Pagination -->
@if($schedules->hasPages())
<div class="px-6 py-4 border-t bg-gray-50">
{{ $schedules->links() }}
</div>
@endif

</div>

</div>

@endsection
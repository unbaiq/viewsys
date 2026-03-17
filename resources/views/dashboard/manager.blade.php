@extends('layouts.app')

@section('header','Screen Dashboard')

@section('content')

<div class="space-y-8">

<!-- SCREEN INFO -->
<div class="bg-white border rounded-xl p-6 shadow-sm">

<div class="flex justify-between items-center">

<div>
<h2 class="text-xl font-bold text-gray-800">
{{ $screen->name ?? 'No Screen Assigned' }}
</h2>

<p class="text-sm text-gray-500">
Location: {{ $screen->location ?? '-' }}
</p>
</div>

<div>

@if($screen && $screen->status)

<span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
Online
</span>

@else

<span class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-full">
Offline
</span>

@endif

</div>

</div>

<div class="mt-4 text-sm text-gray-500">

Last Seen:
{{ $screen?->last_seen ? $screen->last_seen->diffForHumans() : 'Never' }}

</div>

</div>

<!-- STATS -->
<div class="grid md:grid-cols-3 gap-6">

<div class="bg-white border rounded-xl p-6 shadow-sm">

<p class="text-sm text-gray-500">
Media Files
</p>

<h3 class="text-3xl font-bold mt-1">
{{ $stats['media'] }}
</h3>

</div>

<div class="bg-white border rounded-xl p-6 shadow-sm">

<p class="text-sm text-gray-500">
Playlists
</p>

<h3 class="text-3xl font-bold mt-1">
{{ $stats['playlists'] }}
</h3>

</div>

<div class="bg-white border rounded-xl p-6 shadow-sm">

<p class="text-sm text-gray-500">
Schedules
</p>

<h3 class="text-3xl font-bold mt-1">
{{ $stats['schedules'] }}
</h3>

</div>

</div>


<!-- QUICK ACTIONS -->
<div class="bg-white border rounded-xl p-6 shadow-sm">

<h3 class="font-semibold mb-4">
Quick Actions
</h3>

<div class="grid md:grid-cols-4 gap-4">

<a href="/media/create"
class="border rounded-lg p-4 hover:bg-gray-50">

Upload Media

</a>

<a href="/playlists"
class="border rounded-lg p-4 hover:bg-gray-50">

View Playlists

</a>

<a href="/schedules"
class="border rounded-lg p-4 hover:bg-gray-50">

Manage Schedule

</a>

<a href="/screens/{{ $screen->id }}"
class="border rounded-lg p-4 hover:bg-gray-50">

Screen Details

</a>

</div>

</div>


<!-- DEVICE ACTIVITY -->
<div class="bg-white border rounded-xl shadow-sm">

<div class="p-6 border-b">

<h3 class="font-semibold">
Device Activity
</h3>

</div>

<table class="w-full text-sm">

<thead class="bg-gray-50">
<tr>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-left">IP Address</th>
<th class="p-3 text-left">Time</th>
</tr>
</thead>

<tbody>

@foreach($recentLogs as $log)

<tr class="border-t">

<td class="p-3">
{{ $log->status }}
</td>

<td class="p-3">
{{ $log->ip_address }}
</td>

<td class="p-3 text-gray-500">
{{ $log->created_at->diffForHumans() }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection
@extends('layouts.app')

@section('header','Company Dashboard')

@section('content')

<div class="space-y-8">

<!-- HEADER -->
<div class="flex justify-between items-center">

<div>
<h1 class="text-2xl font-bold text-gray-800">
Company Overview
</h1>
<p class="text-sm text-gray-500">
Monitor screens, media and content performance.
</p>
</div>

<div class="flex gap-3">

<a href="/screens/create"
class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
Add Screen
</a>

<a href="/media/create"
class="border px-4 py-2 rounded-lg hover:bg-gray-50">
Upload Media
</a>

</div>

</div>

<!-- STATS -->
<div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">

<div class="bg-white border rounded-xl p-6 shadow-sm">
<p class="text-sm text-gray-500">Total Screens</p>
<h3 class="text-2xl font-bold mt-1">
{{ $stats['screens'] }}
</h3>
</div>

<div class="bg-white border rounded-xl p-6 shadow-sm">
<p class="text-sm text-gray-500">Online Screens</p>
<h3 class="text-2xl font-bold text-green-600 mt-1">
{{ $stats['online_screens'] }}
</h3>
</div>

<div class="bg-white border rounded-xl p-6 shadow-sm">
<p class="text-sm text-gray-500">Offline Screens</p>
<h3 class="text-2xl font-bold text-red-600 mt-1">
{{ $stats['offline_screens'] }}
</h3>
</div>

<div class="bg-white border rounded-xl p-6 shadow-sm">
<p class="text-sm text-gray-500">Media Files</p>
<h3 class="text-2xl font-bold mt-1">
{{ $stats['media'] }}
</h3>
</div>

</div>


<!-- SECOND ROW -->
<div class="grid lg:grid-cols-3 gap-6">

<div class="bg-white border rounded-xl p-6 shadow-sm">

<p class="text-sm text-gray-500">
Company Users
</p>

<h3 class="text-3xl font-bold mt-1">
{{ $stats['users'] }}
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
class="p-4 border rounded-lg hover:bg-gray-50">

Upload Media

</a>

<a href="/playlists/create"
class="p-4 border rounded-lg hover:bg-gray-50">

Create Playlist

</a>

<a href="/schedules/create"
class="p-4 border rounded-lg hover:bg-gray-50">

Schedule Content

</a>

<a href="/screens"
class="p-4 border rounded-lg hover:bg-gray-50">

Manage Screens

</a>

</div>

</div>


<!-- RECENT ACTIVITY -->
<div class="bg-white border rounded-xl shadow-sm">

<div class="p-6 border-b">

<h3 class="font-semibold">
Recent Activity
</h3>

</div>

<table class="w-full text-sm">

<thead class="bg-gray-50">
<tr>
<th class="p-3 text-left">User</th>
<th class="p-3 text-left">Action</th>
<th class="p-3 text-left">Type</th>
<th class="p-3 text-left">Time</th>
</tr>
</thead>

<tbody>

@foreach($recentLogs as $log)

<tr class="border-t">

<td class="p-3">
{{ $log->user->name ?? 'System' }}
</td>

<td class="p-3">
{{ $log->action }}
</td>

<td class="p-3">
{{ $log->type }}
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
@extends('layouts.app')

@section('header','Devices')

@section('content')

<div class="max-w-7xl mx-auto p-6">

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

<!-- Header -->
<div class="flex items-center justify-between px-6 py-5 border-b bg-gradient-to-r from-gray-50 to-white">

<div>
<h2 class="text-lg font-semibold text-gray-800">Devices</h2>
<p class="text-sm text-gray-500">Screens connected to the system</p>
</div>

</div>

<!-- Table -->
<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
<tr>
<th class="px-6 py-4 text-left">Device</th>
<th class="px-6 py-4 text-left">Company</th>
<th class="px-6 py-4 text-left">Status</th>
<th class="px-6 py-4 text-left">Last Activity</th>
<th class="px-6 py-4 text-right">Actions</th>
</tr>
</thead>

<tbody class="divide-y">

@forelse($devices as $device)

<tr class="hover:bg-gray-50">

<!-- Device -->
<td class="px-6 py-4">
<div class="font-medium text-gray-800">
{{ $device->name }}
</div>

<div class="text-xs text-gray-400">
ID: {{ $device->id }}
</div>
</td>

<!-- Company -->
<td class="px-6 py-4 text-gray-600">
{{ $device->company->name ?? '-' }}
</td>

<!-- Status -->
<td class="px-6 py-4">

@php
$online = $device->lastLog && $device->lastLog->created_at->diffInMinutes(now()) < 5;
@endphp

@if($online)

<span class="px-3 py-1 text-xs rounded-full bg-green-50 text-green-700 font-medium">
Online
</span>

@else

<span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">
Offline
</span>

@endif

</td>

<!-- Last Activity -->
<td class="px-6 py-4 text-gray-500">

@if($device->lastLog)

{{ $device->lastLog->created_at->diffForHumans() }}

@else

Never

@endif

</td>

<!-- Actions -->
<td class="px-6 py-4 text-right">

<a href="{{ route('devices.show',$device) }}"
class="text-indigo-600 hover:text-indigo-800 font-medium mr-4">
View
</a>

<form method="POST"
action="{{ route('devices.restart',$device) }}"
class="inline">
@csrf

<button onclick="return confirm('Restart this device?')"
class="text-orange-600 hover:text-orange-800 font-medium">
Restart
</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center py-14">

<div class="flex flex-col items-center">

<div class="bg-gray-100 p-4 rounded-full mb-3">
🖥️
</div>

<p class="text-gray-600 font-medium">
No devices connected
</p>

</div>

</td>
</tr>

@endforelse

</tbody>

</table>

</div>

@if($devices->hasPages())
<div class="px-6 py-4 border-t bg-gray-50">
{{ $devices->links() }}
</div>
@endif

</div>

</div>

@endsection
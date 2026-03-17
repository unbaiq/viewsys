@extends('layouts.app')

@section('header','Device Details')

@section('content')

<div class="max-w-6xl mx-auto p-6 space-y-6">

<!-- Device Info -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

<div class="flex items-center justify-between">

<div>
<h2 class="text-lg font-semibold text-gray-800">
{{ $screen->name }}
</h2>

<p class="text-sm text-gray-500">
Company: {{ $screen->company->name ?? '-' }}
</p>
</div>

<form method="POST" action="{{ route('devices.restart',$screen) }}">
@csrf

<button onclick="return confirm('Restart device?')"
class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
Restart Device
</button>

</form>

</div>

</div>

<!-- Logs -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

<div class="px-6 py-5 border-b">
<h3 class="text-md font-semibold text-gray-800">
Device Logs
</h3>
</div>

<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600 text-xs uppercase">
<tr>
<th class="px-6 py-3 text-left">Time</th>
<th class="px-6 py-3 text-left">Event</th>
<th class="px-6 py-3 text-left">Details</th>
</tr>
</thead>

<tbody class="divide-y">

@foreach($logs as $log)

<tr>

<td class="px-6 py-4 text-gray-500">
{{ $log->created_at->format('d M Y H:i') }}
</td>

<td class="px-6 py-4 font-medium text-gray-700">
{{ $log->event ?? 'Activity' }}
</td>

<td class="px-6 py-4 text-gray-500">
{{ $log->message ?? '-' }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@if($logs->hasPages())
<div class="px-6 py-4 border-t">
{{ $logs->links() }}
</div>
@endif

</div>

</div>

@endsection
@extends('layouts.app')

@section('header','System Logs')

@section('content')

<div class="bg-white shadow-sm rounded-2xl border border-gray-100">

{{-- FILTER BAR --}}
<div class="p-6 border-b bg-gray-50 rounded-t-2xl">

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

<form class="flex flex-wrap items-center gap-3">

<input
name="search"
value="{{ request('search') }}"
placeholder="Search logs..."
class="border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none rounded-lg px-4 py-2 text-sm w-56">

<select name="type"
class="border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-3 py-2 text-sm">

<option value="">All Types</option>

<option value="login" {{ request('type')=='login' ? 'selected' : '' }}>
Login
</option>

<option value="media_upload" {{ request('type')=='media_upload' ? 'selected' : '' }}>
Media Upload
</option>

<option value="screen_update" {{ request('type')=='screen_update' ? 'selected' : '' }}>
Screen Update
</option>

<option value="error" {{ request('type')=='error' ? 'selected' : '' }}>
Error
</option>

<option value="system" {{ request('type')=='system' ? 'selected' : '' }}>
System
</option>

</select>

<button
class="bg-gray-900 hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition">
Filter
</button>

@if(request()->hasAny(['search','type']))
<a href="{{ route('logs.index') }}"
class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-lg transition">
Reset
</a>
@endif

</form>


<form method="POST"
action="{{ route('logs.clear') }}"
onsubmit="return confirm('Are you sure you want to clear all logs?')">
@csrf

<button
class="bg-red-600 hover:bg-red-700 text-white text-sm px-5 py-2 rounded-lg shadow-sm">
Clear Logs
</button>

</form>

</div>

</div>


{{-- TABLE --}}
<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600">

<tr>
<th class="px-6 py-4 text-left font-semibold">User</th>
<th class="px-6 py-4 text-left font-semibold">Type</th>
<th class="px-6 py-4 text-left font-semibold">Action</th>
<th class="px-6 py-4 text-left font-semibold">IP</th>
<th class="px-6 py-4 text-left font-semibold">Date</th>
<th class="px-6 py-4 text-right font-semibold">Details</th>
</tr>

</thead>

<tbody class="divide-y divide-gray-100">

@forelse($logs as $log)

<tr class="hover:bg-gray-50 transition">

<td class="px-6 py-4 font-medium text-gray-800">
{{ $log->user->name ?? 'System' }}
</td>


<td class="px-6 py-4">

<span class="px-3 py-1 text-xs font-medium rounded-full

@if($log->type == 'login')
bg-blue-100 text-blue-700

@elseif($log->type == 'media_upload')
bg-purple-100 text-purple-700

@elseif($log->type == 'screen_update')
bg-indigo-100 text-indigo-700

@elseif($log->type == 'error')
bg-red-100 text-red-600

@else
bg-gray-100 text-gray-700
@endif
">

{{ ucfirst(str_replace('_',' ',$log->type)) }}

</span>

</td>


<td class="px-6 py-4 text-gray-700">
{{ $log->action }}
</td>


<td class="px-6 py-4 text-gray-600 text-sm">
{{ $log->ip }}
</td>


<td class="px-6 py-4 text-gray-500 text-sm">
{{ $log->created_at->diffForHumans() }}
</td>


<td class="px-6 py-4 text-right">

<a href="{{ route('logs.show',$log) }}"
class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
View
</a>

</td>

</tr>

@empty

<tr>
<td colspan="6" class="text-center py-12 text-gray-500">
No logs found.
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


{{-- PAGINATION --}}
@if($logs->hasPages())
<div class="p-6 border-t bg-gray-50 rounded-b-2xl">
{{ $logs->links() }}
</div>
@endif


</div>

@endsection
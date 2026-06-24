@extends('layouts.app')

@section('header','Device Details')

@section('content')

<div class="max-w-8xl mx-auto space-y-5">

<!-- ================= DEVICE HEADER ================= -->
<div class="bg-white border border-slate-200 rounded-xl px-5 py-4 flex items-center justify-between">

    <div>
        <div class="text-base font-semibold text-slate-900">
            {{ $screen->name }}
        </div>
        <div class="text-xs text-slate-500">
            {{ $screen->company->name ?? '-' }}
        </div>
    </div>

    <form method="POST" action="{{ route('devices.restart',$screen) }}"
          onsubmit="return confirm('Restart device?')">
        @csrf

        <button
            class="px-4 py-2 text-xs bg-orange-600 text-white rounded-lg hover:bg-orange-700">
            Restart
        </button>
    </form>

</div>


<!-- ================= LOGS ================= -->
<div class="bg-white border border-slate-200 rounded-xl">

<div class="px-5 py-3 border-b text-sm font-medium text-slate-700">
    Device Logs
</div>

<div class="overflow-x-auto">

<table class="w-full text-xs">

<thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
<tr>
    <th class="px-5 py-3 text-left">Time</th>
    <th class="text-left">Event</th>
    <th class="text-left">Details</th>
</tr>
</thead>

<tbody class="divide-y">

@forelse($logs as $log)

<tr class="hover:bg-slate-50">

<td class="px-5 py-3 text-slate-500">
    {{ $log->created_at->format('d M Y, H:i') }}
</td>

<td class="font-medium text-slate-700">
    {{ $log->event ?? 'Activity' }}
</td>

<td class="text-slate-500">
    {{ $log->message ?? '-' }}
</td>

</tr>

@empty

<tr>
<td colspan="3" class="text-center py-12 text-slate-400">
    No logs available
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

@if($logs->hasPages())
<div class="px-5 py-3 border-t bg-slate-50">
    {{ $logs->links() }}
</div>
@endif

</div>

</div>

@endsection
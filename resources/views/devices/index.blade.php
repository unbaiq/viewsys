@extends('layouts.app')

@section('header','Devices')

@section('content')

<div class="max-w-7xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- ================= HEADER ================= -->
<div class="px-5 py-4 border-b flex items-center justify-between">

    <div>
        <h2 class="text-base font-semibold text-slate-900">Devices</h2>
        <p class="text-xs text-slate-500">Connected screens</p>
    </div>

</div>

<!-- ================= TABLE ================= -->
<div class="overflow-x-auto">

<table class="w-full text-xs">

<thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
<tr>
    <th class="px-5 py-3 text-left">Device</th>
    <th class="text-left">Company</th>
    <th class="text-left">Status</th>
    <th class="text-left">Last Activity</th>
    <th class="text-right pr-5">Actions</th>
</tr>
</thead>

<tbody class="divide-y">

@forelse($devices as $device)

<tr class="hover:bg-slate-50 transition">

<!-- DEVICE -->
<td class="px-5 py-3">
    <div class="font-medium text-slate-800">{{ $device->name }}</div>
    <div class="text-[11px] text-slate-500">ID: {{ $device->id }}</div>
</td>

<!-- COMPANY -->
<td class="text-slate-600">
    {{ $device->company->name ?? '-' }}
</td>

<!-- STATUS -->
<td>
@php
$online = $device->lastLog && $device->lastLog->created_at->diffInMinutes(now()) < 5;
@endphp

@if($online)
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">
        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
        Online
    </span>
@else
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 font-medium">
        <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
        Offline
    </span>
@endif

</td>

<!-- LAST ACTIVITY -->
<td class="text-slate-500">
    {{ $device->lastLog ? $device->lastLog->created_at->diffForHumans() : 'Never' }}
</td>

<!-- ACTIONS -->
<td class="text-right pr-5">
    <div class="flex justify-end gap-2">

        <a href="{{ route('devices.show',$device) }}"
           class="p-2 rounded hover:bg-indigo-50 text-indigo-600"
           title="View">
            <i class="fa fa-eye text-xs"></i>
        </a>

        <form method="POST"
              action="{{ route('devices.restart',$device) }}"
              onsubmit="return confirm('Restart this device?')">
            @csrf

            <button
                class="p-2 rounded hover:bg-orange-50 text-orange-600"
                title="Restart">
                <i class="fa fa-rotate-right text-xs"></i>
            </button>

        </form>

    </div>
</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center py-14 text-slate-400">

    <div class="flex flex-col items-center gap-2">
        <div class="text-2xl">🖥️</div>
        <span>No devices connected</span>
    </div>

</td>
</tr>

@endforelse

</tbody>

</table>

</div>

<!-- ================= PAGINATION ================= -->
@if($devices->hasPages())
<div class="px-5 py-3 border-t bg-slate-50">
    {{ $devices->links() }}
</div>
@endif

</div>

</div>

@endsection
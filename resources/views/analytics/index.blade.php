@extends('layouts.app')

@section('header','Analytics')

@section('content')

<div class="h-full overflow-y-auto px-4 py-4 space-y-4">

<!-- ================= TOP METRICS ================= -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">

<div class="bg-white border rounded-lg p-4 flex justify-between items-center">
    <div>
        <div class="text-[11px] text-slate-500">Total Screens</div>
        <div class="text-xl font-semibold">{{ $totalScreens }}</div>
    </div>
    <div class="text-indigo-500 text-lg">🖥️</div>
</div>

<div class="bg-white border rounded-lg p-4 flex justify-between items-center">
    <div>
        <div class="text-[11px] text-slate-500">Online</div>
        <div class="text-xl font-semibold text-green-600">{{ $onlineScreens }}</div>
    </div>
    <div class="text-green-500 text-lg">🟢</div>
</div>

<div class="bg-white border rounded-lg p-4 flex justify-between items-center">
    <div>
        <div class="text-[11px] text-slate-500">Offline</div>
        <div class="text-xl font-semibold text-red-600">{{ $offlineScreens }}</div>
    </div>
    <div class="text-red-500 text-lg">🔴</div>
</div>

<div class="bg-white border rounded-lg p-4 flex justify-between items-center">
    <div>
        <div class="text-[11px] text-slate-500">Media Files</div>
        <div class="text-xl font-semibold">{{ $totalMedia }}</div>
    </div>
    <div class="text-purple-500 text-lg">🎬</div>
</div>

</div>


<!-- ================= SECOND ROW ================= -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">

<div class="bg-white border rounded-lg p-4">
    <div class="text-[11px] text-slate-500">Playlists</div>
    <div class="text-lg font-semibold">{{ $totalPlaylists }}</div>
</div>

@if($companies)
<div class="bg-white border rounded-lg p-4">
    <div class="text-[11px] text-slate-500">Companies</div>
    <div class="text-lg font-semibold">{{ $companies }}</div>
</div>
@endif

<div class="bg-white border rounded-lg p-4">
    <div class="text-[11px] text-slate-500">Storage Used</div>
    <div class="text-lg font-semibold">{{ $storageGB }} GB</div>
</div>

<div class="bg-white border rounded-lg p-4">
    <div class="text-[11px] text-slate-500">Today Activity</div>
    <div class="text-[11px] mt-1">
        <span class="text-green-600 font-medium">Online: {{ $todayOnline }}</span><br>
        <span class="text-red-600 font-medium">Offline: {{ $todayOffline }}</span>
    </div>
</div>

</div>


<!-- ================= GRAPH ================= -->
<div class="bg-white border rounded-lg p-4">

<div class="flex justify-between items-center mb-3">
    <h3 class="text-sm font-semibold">Today Activity (Hourly)</h3>
    <span class="text-xs text-slate-400">Last 24 hours</span>
</div>

<canvas id="chart" height="90"></canvas>

</div>


<!-- ================= RECENT ACTIVITY ================= -->
<div class="bg-white border rounded-lg p-4">

<div class="flex justify-between items-center mb-3">
    <h3 class="text-sm font-semibold">Recent Device Activity</h3>
    <span class="text-xs text-slate-400">Latest 10 logs</span>
</div>

<div class="divide-y">

@foreach($recentLogs as $log)

<div class="flex justify-between items-center py-2 text-xs">

<div class="flex items-center gap-2">
    <span class="w-2 h-2 rounded-full 
        {{ $log->status=='online'?'bg-green-500':'bg-red-500' }}"></span>
    Screen #{{ $log->screen_id }}
</div>

<div class="{{ $log->status=='online'?'text-green-600':'text-red-600' }}">
    {{ ucfirst($log->status) }}
</div>

<div class="text-slate-400">
    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
</div>

</div>

@endforeach

</div>

</div>

</div>


<!-- ================= CHART ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('chart'), {
    type: 'line',
    data: {
        labels: [...Array(24).keys()],
        datasets: [{
            label: 'Activity',
            data: @json($hourly),
            tension: 0.4,
            fill: false
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

@endsection
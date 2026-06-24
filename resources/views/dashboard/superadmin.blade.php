@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="h-[calc(100vh-80px)] overflow-y-auto px-4 md:px-8 py-6 space-y-6 bg-slate-50/50 font-sans antialiased text-slate-600">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/60 pb-5">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">Dashboard</h1>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Real-time system overview & analytics hub</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="/companies" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition shadow-sm">
                + Company
            </a>
            <a href="/screens/create" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm shadow-indigo-100">
                + New Screen
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Companies</p>
            <h2 class="text-2xl font-black mt-2 text-slate-900 tracking-tight">{{ $stats['companies'] ?? '-' }}</h2>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Users</p>
            <h2 class="text-2xl font-black mt-2 text-slate-900 tracking-tight">{{ $stats['users'] ?? '-' }}</h2>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Media Library</p>
            <h2 class="text-2xl font-black mt-2 text-slate-900 tracking-tight">{{ $stats['media'] ?? 0 }}</h2>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-emerald-500"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Online Screens</p>
            <div class="flex items-baseline gap-2 mt-2">
                <h2 class="text-2xl font-black text-emerald-600 tracking-tight">{{ $stats['online_screens'] ?? 0 }}</h2>
                <span class="text-xs font-semibold text-slate-400">/ {{ $stats['screens'] }} total</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between group relative overflow-hidden col-span-2 sm:col-span-1">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-rose-500"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Offline Screens</p>
            <h2 class="text-2xl font-black mt-2 text-rose-500 tracking-tight">{{ $stats['offline_screens'] ?? 0 }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm lg:col-span-2 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight">System Operational Trend</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Accumulated hardware node communication logs</p>
                </div>
                <span class="text-[10px] font-bold bg-slate-100 text-slate-500 p-1.5 px-2.5 rounded-lg border border-slate-200/30">Total: {{ array_sum($activityData) }} hits</span>
            </div>
            <div class="mt-4 h-48">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm tracking-tight">Screen Health Proportion</h3>
                <p class="text-[11px] text-slate-400 font-medium">Live connectivity segment footprint</p>
            </div>
            
            <div class="relative max-h-36 my-auto flex justify-center">
                <canvas id="pieChart"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-2 border-t border-slate-50 pt-3 text-center text-xs font-bold">
                <div class="bg-emerald-50/50 rounded-xl p-1.5 border border-emerald-100/30 text-emerald-700">
                    Online: {{ $chartOnline }}
                </div>
                <div class="bg-rose-50/50 rounded-xl p-1.5 border border-rose-100/30 text-rose-700">
                    Offline: {{ $chartOffline }}
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm lg:col-span-2 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm tracking-tight">Media Content Injection</h3>
                <p class="text-[11px] text-slate-400 font-medium">Asset libraries updates verified weekly</p>
            </div>
            <div class="mt-4 h-44">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm tracking-tight">Cloud Storage Capacity</h3>
                <p class="text-[11px] text-slate-400 font-medium">Global partition allocation boundaries</p>
            </div>

            <div class="my-auto py-3">
                <div class="flex justify-between items-baseline mb-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $storagePercent }}%</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Used Resource Boundary</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden p-0.5 border border-slate-200/20">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-1.5 rounded-full transition-all duration-500"
                         style="width: {{ $storagePercent }}%"></div>
                </div>
            </div>

            <div class="text-[11px] font-medium bg-slate-50 rounded-xl p-3 text-slate-500 border border-slate-100 flex items-start gap-2">
                <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>SLA guidelines recommend cleaning cached instances under 80% thresholds.</span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-slate-50/40">
            <div>
                <h3 class="font-bold text-slate-900 text-sm tracking-tight">Company Node Connectivity Distribution</h3>
                <p class="text-[11px] text-slate-400 font-medium">Active operational metric health metrics categorized by organizational tenant</p>
            </div>
            <div class="flex items-center gap-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Online</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-400"></span> Offline</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/10 border-b border-slate-200/60 text-[11px] font-bold tracking-widest text-slate-400 uppercase">
                        <th class="px-6 py-3.5">Company Client</th>
                        <th class="px-6 py-3.5">Deployment Ratio</th>
                        <th class="px-6 py-3.5">Proportional Health Index</th>
                        <th class="px-6 py-3.5 text-right">Status Summary</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                    @forelse($companiesData ?? [] as $company)
                        <tr class="hover:bg-slate-50/30 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900 text-sm block tracking-tight">{{ $company->name }}</span>
                                <span class="text-[10px] text-slate-400 tracking-normal block mt-0.5">ID: #{{ $company->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold text-xs">
                                    {{ $company->online_screens_count ?? 0 }} <span class="text-slate-400 font-semibold font-mono">/ {{ $company->screens_count ?? 0 }} Nodes</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 w-64">
                                @php
                                    $total = $company->screens_count ?? 0;
                                    $online = $company->online_screens_count ?? 0;
                                    $percent = $total > 0 ? round(($online / $total) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden flex">
                                        <div class="bg-emerald-500 h-2 transition-all" style="width: {{ $percent }}%"></div>
                                        <div class="bg-rose-400 h-2 transition-all" style="width: {{ 100 - $percent }}%"></div>
                                    </div>
                                    <span class="font-bold font-mono text-slate-700 min-w-[28px] text-right">{{ $percent }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        ▲ {{ $company->online_screens_count ?? 0 }} Up
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                        ▼ {{ ($company->screens_count ?? 0) - ($company->online_screens_count ?? 0) }} Down
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-xs text-slate-400">
                                No specific organizational uptime distributions mapped into records yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/40">
            <div>
                <h3 class="font-bold text-slate-900 text-sm tracking-tight">Recent Activity Log</h3>
                <p class="text-[11px] text-slate-400 font-medium">Real-time immutable operation stream triggers</p>
            </div>
            <a href="/logs" class="text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100/40 p-1.5 px-3 rounded-xl transition">
                View All Audit
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($recentLogs as $log)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50/40 transition text-xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-7 w-7 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 border border-slate-200/50 flex-shrink-0">
                            {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 block truncate">{{ $log->user->name ?? 'System Process' }}</span>
                            <span class="text-slate-400 font-medium text-[11px] block md:hidden mt-0.5">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="text-slate-600 font-medium max-w-xs md:max-w-md truncate px-4">
                        <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-700 border border-slate-200/40 font-mono text-[11px] mr-1.5">Action</span>{{ $log->action }}
                    </div>

                    <div class="text-slate-400 font-semibold hidden md:block">
                        {{ $log->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-slate-400 font-medium">
                    No system event modifications captured during this sequence window.
                </div>
            @endforelse
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// LINE CHART
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['6d','5d','4d','3d','2d','1d','Today'],
        datasets: [{
            data: {!! json_encode(isset($activityData) ? $activityData : []) !!},
            borderColor: '#6366f1',
            borderWidth: 2.5,
            backgroundColor: 'rgba(99, 102, 241, 0.03)',
            tension: 0.38,
            fill: true,
            pointBackgroundColor: '#6366f1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { 
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, color: '#94a3b8' }, beginAtZero: true },
            x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: '#94a3b8' } }
        }
    }
});

// DONUT DOUGHNUT CHART
new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Online','Offline'],
        datasets: [{
            data: [{{ $chartOnline ?? 0 }}, {{ $chartOffline ?? 0 }}],
            backgroundColor: ['#10b981', '#f43f5e'],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '75%'
    }
});

// BAR CHART
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['6d','5d','4d','3d','2d','1d','Today'],
        datasets: [{
            data: {!! json_encode(isset($mediaData) ? $mediaData : []) !!},
            backgroundColor: '#4f46e5',
            borderRadius: 4,
            maxBarThickness: 16
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { 
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, color: '#94a3b8', precision: 0 }, beginAtZero: true },
            x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: '#94a3b8' } }
        }
    }
});
</script>
@endsection
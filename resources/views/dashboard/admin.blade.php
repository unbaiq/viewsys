@extends('layouts.app')

@section('header', 'Admin Dashboard')

@section('content')
<div class="space-y-8  md:p-8 max-w-[1600px] mx-auto bg-slate-50/50 min-h-screen font-sans antialiased text-slate-600">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-200/60 pb-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-black tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 bg-clip-text text-transparent">
                    Control Hub
                </h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100/80 tracking-wide uppercase shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    v2.4 Live
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1.5 font-medium max-w-xl">
                Monitor live company screens, manage active playlists, and track overall network connectivity parameters.
            </p>
        </div>

        <div class="flex items-center gap-3 w-full lg:w-auto">
            <a href="/media/create" class="flex-1 lg:flex-initial text-center border border-slate-200/80 text-slate-700 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm bg-white">
                Upload Media
            </a>
            <a href="/screens/create" class="flex-1 lg:flex-initial text-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm hover:shadow-lg hover:shadow-indigo-100 transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add New Screen
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex items-center justify-between group relative overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-slate-100 group-hover:bg-indigo-500 transition-colors duration-200"></div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Deployments</p>
                <h2 class="text-3xl font-black mt-2 text-slate-900 tracking-tight">{{ $stats['screens'] ?? 0 }}</h2>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 text-slate-400 border border-slate-100 group-hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex items-center justify-between group relative overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-slate-100 group-hover:bg-emerald-500 transition-colors duration-200"></div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Active Nodes</p>
                <div class="flex items-center gap-2 mt-2">
                    <h2 class="text-3xl font-black text-emerald-600 tracking-tight">{{ $stats['online_screens'] ?? 0 }}</h2>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
            </div>
            <div class="p-3 rounded-xl bg-emerald-50/50 text-emerald-600 border border-emerald-100/40 group-hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex items-center justify-between group relative overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-slate-100 group-hover:bg-rose-500 transition-colors duration-200"></div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Offline Alert State</p>
                <h2 class="text-3xl font-black mt-2 text-rose-500 tracking-tight">{{ $stats['offline_screens'] ?? 0 }}</h2>
            </div>
            <div class="p-3 rounded-xl bg-rose-50/50 text-rose-500 border border-rose-100/40 group-hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex items-center justify-between group relative overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-slate-100 group-hover:bg-indigo-600 transition-colors duration-200"></div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Network Operational SLA</p>
                <h2 class="text-3xl font-black mt-2 text-indigo-600 tracking-tight">{{ $uptime ?? 0 }}%</h2>
            </div>
            <div class="p-3 rounded-xl bg-indigo-50/50 text-indigo-600 border border-indigo-100/40 group-hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm xl:col-span-2 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-bold text-slate-900 tracking-tight text-base">Daywise Screen Connectivity</h3>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">7-day validation of hardware up/down telemetry states</p>
                </div>
                <div class="flex items-center gap-3 text-[11px] font-extrabold text-slate-500 bg-slate-50 border border-slate-200/60 p-1.5 px-3 rounded-xl">
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>ONLINE</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-rose-500"></span>OFFLINE</span>
                </div>
            </div>
            <div class="mt-6 h-60">
                <canvas id="daywiseStackedChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 tracking-tight text-base">Network Proportions</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Active structural unit presence ratios</p>
            </div>
            <div class="relative max-h-48 my-auto w-full flex justify-center py-2">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm xl:col-span-2 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 tracking-tight text-base">Media Upload Ingestion</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Asset content distribution metrics tracked weekly</p>
            </div>
            <div class="mt-6 h-60">
                <canvas id="mediaChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 tracking-tight text-base">Cloud Volume Storage</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Dynamic media partition capacity utilization</p>
            </div>
            
            <div class="my-auto py-6">
                <div class="flex justify-between items-baseline mb-2.5">
                    <span class="text-4xl font-black text-slate-900 tracking-tight">{{ $storagePercent }}%</span>
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Used of 10 GB limit</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200/20">
                    <div class="bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 h-2 rounded-full transition-all duration-500" style="width: {{ $storagePercent }}%"></div>
                </div>
            </div>

            <div class="bg-slate-50/80 rounded-xl p-3.5 border border-slate-100 flex items-start gap-3 text-xs text-slate-500 font-medium">
                <svg class="w-4 h-4 text-indigo-500 mt-0.5 flex-shrink-0 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Automate cleaner retention routines by reviewing legacy asset folders regularly.</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
            <div>
                <h3 class="font-bold text-slate-900 tracking-tight text-base">Real-time Terminal Matrix</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Top 5 primary connection configurations</p>
            </div>
            <span class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100/60 tracking-widest uppercase">Live Stream Feed</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/20 border-b border-slate-200/60 text-[11px] font-bold tracking-widest text-slate-400 uppercase">
                        <th class="px-6 py-4">Terminal Designation</th>
                        <th class="px-6 py-4">SLA Status</th>
                        <th class="px-6 py-4">Last Activity Check</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600 font-medium">
                    @forelse($screenStatusReport->take(5) as $report)
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $report->name }}</td>
                            <td class="px-6 py-4">
                                @if($report->current_status === 'Online')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/40 shadow-sm">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $report->current_status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-md bg-rose-50 text-rose-700 border border-rose-200/40 shadow-sm">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                                        {{ $report->current_status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-semibold">{{ $report->last_seen_human }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-sm text-slate-400 bg-slate-50/10">
                                <svg class="mx-auto h-12 w-12 text-slate-200 mb-3 stroke-[1.25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="font-semibold text-slate-400 block">No screens registered on company database yet.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Distribution Chart Configuration
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Online', 'Offline'],
            datasets: [{
                data: [{{ $chartOnline ?? 0 }}, {{ $chartOffline ?? 0 }}],
                backgroundColor: ['#10b981', '#f43f5e'],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { boxWidth: 8, usePointStyle: true, font: { size: 11, weight: '700' }, padding: 20, color: '#475569' }
                }
            },
            cutout: '82%'
        }
    });

    // Daywise Stacked Up / Down Screen Bar Chart Configuration
    new Chart(document.getElementById('daywiseStackedChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(isset($daysData['labels']) ? $daysData['labels'] : ['6d', '5d', '4d', '3d', '2d', '1d', 'Today']) !!},
            datasets: [
                {
                    label: 'Online',
                    data: {!! json_encode(isset($daysData['online']) ? $daysData['online'] : []) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                },
                {
                    label: 'Offline',
                    data: {!! json_encode(isset($daysData['offline']) ? $daysData['offline'] : []) !!},
                    backgroundColor: '#f43f5e',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: '700' }, color: '#94a3b8' }
                },
                y: {
                    stacked: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11, weight: '500' }, color: '#94a3b8', precision: 0 }
                }
            }
        }
    });

    // Media Uploads Bar Graph Configuration
    new Chart(document.getElementById('mediaChart'), {
        type: 'bar',
        data: {
            labels: ['6d', '5d', '4d', '3d', '2d', '1d', 'Today'],
            datasets: [{
                label: 'Uploads',
                data: {!! json_encode(isset($mediaData) ? $mediaData : []) !!},
                backgroundColor: '#6366f1',
                borderRadius: 5,
                borderSkipped: false,
                maxBarThickness: 14
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 11, weight: '500' }, color: '#94a3b8', precision: 0 } },
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: '700' }, color: '#94a3b8' } }
            }
        }
    });
</script>
@endsection
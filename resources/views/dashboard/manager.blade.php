@extends('layouts.app')

@section('header', 'Screen Dashboard')

@section('content')
<div class="space-y-6 p-4 md:p-8 max-w-[1600px] mx-auto bg-slate-50/50 min-h-screen font-sans antialiased text-slate-600">

    <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-indigo-600"></div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-2xl font-black tracking-tight text-slate-900">
                        {{ $screen->name ?? 'No Screen Assigned' }}
                    </h2>
                    
                    @if($screen && $screen->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/40 shadow-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Node Online
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-md bg-rose-50 text-rose-700 border border-rose-200/40 shadow-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                            Disconnected Offline
                        </span>
                    @endif
                </div>

                <p class="text-sm text-slate-500 mt-1.5 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    Deployment Location: <span class="text-slate-700 font-semibold">{{ $screen->location ?? 'Unspecified' }}</span>
                </p>
            </div>

            <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-200/40 text-xs font-semibold text-slate-400 sm:text-right w-full sm:w-auto">
                System Last Seen Pulse
                <span class="block text-slate-800 font-bold text-sm mt-0.5 font-mono">
                    {{ optional($screen?->last_seen)->diffForHumans() ?? 'Never Detected' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between group">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Media Files</p>
                <h3 class="text-3xl font-black mt-2 text-slate-900 tracking-tight">{{ $stats['media'] ?? 0 }}</h3>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 text-slate-400 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 stroke-[1.75]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between group">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Active Playlists</p>
                <h3 class="text-3xl font-black mt-2 text-slate-900 tracking-tight">{{ $stats['playlists'] ?? 0 }}</h3>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 text-slate-400 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 stroke-[1.75]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 5.25h16.5m-16.5-10.5h16.5"/></svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between group col-span-1 sm:col-span-2 lg:col-span-1">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Assigned Schedules</p>
                <h3 class="text-3xl font-black mt-2 text-slate-900 tracking-tight">{{ $stats['schedules'] ?? 0 }}</h3>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 text-slate-400 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 stroke-[1.75]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
        <div class="mb-4">
            <h3 class="font-bold text-slate-900 tracking-tight text-base">Quick Operator Dashboard Actions</h3>
            <p class="text-xs text-slate-400 font-medium">Quick link utilities mapped to direct component endpoints</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="/media/create" class="group border border-slate-200/80 rounded-xl p-4 bg-white hover:bg-slate-50/50 hover:border-indigo-200 hover:shadow-sm transition duration-150 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-indigo-600 transition-colors">Media Library</span>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Upload asset items to use in sequences.</p>
                </div>
                <div class="text-slate-700 font-bold text-sm flex items-center justify-between mt-4 border-t border-slate-50 pt-2.5">
                    <span>Upload Media</span>
                    <span class="text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all">→</span>
                </div>
            </a>

            <a href="/playlists" class="group border border-slate-200/80 rounded-xl p-4 bg-white hover:bg-slate-50/50 hover:border-indigo-200 hover:shadow-sm transition duration-150 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-indigo-600 transition-colors">Asset Pipelines</span>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Review and structuralize broadcast streams.</p>
                </div>
                <div class="text-slate-700 font-bold text-sm flex items-center justify-between mt-4 border-t border-slate-50 pt-2.5">
                    <span>View Playlists</span>
                    <span class="text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all">→</span>
                </div>
            </a>

            <a href="/schedules" class="group border border-slate-200/80 rounded-xl p-4 bg-white hover:bg-slate-50/50 hover:border-indigo-200 hover:shadow-sm transition duration-150 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-indigo-600 transition-colors">Automation</span>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Map structural timelines across nodes.</p>
                </div>
                <div class="text-slate-700 font-bold text-sm flex items-center justify-between mt-4 border-t border-slate-50 pt-2.5">
                    <span>Manage Schedule</span>
                    <span class="text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all">→</span>
                </div>
            </a>

            @if($screen)
                <a href="{{ route('screens.show', $screen->id) }}" class="group border border-slate-200/80 rounded-xl p-4 bg-white hover:bg-indigo-600 hover:border-indigo-600 hover:shadow-md hover:shadow-indigo-100 transition duration-150 flex flex-col justify-between text-slate-700 hover:text-white">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-indigo-200 transition-colors">Configuration</span>
                        <p class="text-xs text-slate-400 mt-1 font-medium group-hover:text-indigo-100/80">Audit advanced client variables & metadata.</p>
                    </div>
                    <div class="font-bold text-sm flex items-center justify-between mt-4 border-t border-slate-50/10 pt-2.5">
                        <span>Screen Details</span>
                        <span class="text-slate-300 group-hover:text-white group-hover:translate-x-0.5 transition-all">→</span>
                    </div>
                </a>
            @else
                <div class="border border-dashed border-slate-200 rounded-xl p-4 bg-slate-50/40 text-slate-400 flex flex-col justify-center items-center text-center text-xs font-medium">
                    <svg class="w-5 h-5 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    No Screen Context Available
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
            <div>
                <h3 class="font-bold text-slate-900 tracking-tight text-base">Terminal Device Activity Trail</h3>
                <p class="text-xs text-slate-400 font-medium">Real-time telemetry heartbeat metrics logged to system memory</p>
            </div>
            <span class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100/40 px-2.5 py-1 rounded-md tracking-wider uppercase">Live Logs Feed</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/20 border-b border-slate-200/60 text-[11px] font-bold tracking-widest text-slate-400 uppercase">
                        <th class="px-6 py-3.5">System Status Event</th>
                        <th class="px-6 py-3.5">Terminal IP Address</th>
                        <th class="px-6 py-3.5 text-right">Event Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                    @forelse($recentLogs ?? [] as $log)
                        <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                            <td class="px-6 py-4">
                                @if(strtolower($log->status ?? '') === 'online' || $log->status === 'Success' || $log->status === 'Active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 font-bold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/40">
                                        <span class="h-1 w-1 rounded-full bg-emerald-500"></span>
                                        {{ $log->status }}
                                    </span>
                                @elseif(strtolower($log->status ?? '') === 'offline' || $log->status === 'Error' || $log->status === 'Failure')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 font-bold rounded-md bg-rose-50 text-rose-700 border border-rose-200/40">
                                        <span class="h-1 w-1 rounded-full bg-rose-400"></span>
                                        {{ $log->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 font-bold rounded-md bg-slate-100 text-slate-700 border border-slate-200/60">
                                        {{ $log->status ?? '-' }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 font-mono font-bold text-slate-800 tracking-tight">
                                {{ $log->ip_address ?? '0.0.0.0' }}
                            </td>
                            
                            <td class="px-6 py-4 text-right text-slate-400 font-semibold">
                                {{ optional($log->created_at)->diffForHumans() ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-16 text-slate-400 bg-slate-50/10">
                                <svg class="mx-auto h-12 w-12 text-slate-200 mb-3 stroke-[1.25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="font-semibold block text-sm tracking-tight text-slate-400">No telemetry records discovered</span>
                                <span class="text-xs block text-slate-400/80 mt-0.5">This screen container has not produced runtime log streams yet.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

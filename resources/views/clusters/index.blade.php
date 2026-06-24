@extends('layouts.app')

@section('header','Clusters')

@section('content')

<div class="max-w-8xl mx-auto space-y-4">

    @if(session('success'))
        <div id="flash-alert" class="flex items-center p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-check-circle mr-2"></i>
            <div>
                <span class="font-semibold">Success!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-alert" class="flex items-center p-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-exclamation-circle mr-2"></i>
            <div>
                <span class="font-semibold">Alert!</span> {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">

        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Clusters</h2>
                <p class="text-xs text-slate-500">Manage screen groups & monitor devices</p>
            </div>

            <a href="{{ route('clusters.create') }}"
               class="bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fa-solid fa-plus mr-1"></i> Add Cluster
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide text-[11px]">
                    <tr>
                        <th class="px-6 py-3 text-left">Cluster</th>
                        <th class="text-left">Layout</th>
                        <th class="text-left">Screens</th>
                        <th class="text-left">Status</th>
                        <th class="text-right pr-6">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($clusters as $cluster)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800">
                                {{ $cluster->name }}
                            </div>
                            <div class="text-[11px] text-slate-500">
                                {{ $cluster->description ?? 'No description' }}
                            </div>
                        </td>

                        <td>
                            <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-medium capitalize text-[11px]">
                                {{ str_replace('_',' ',$cluster->type) }}
                            </span>
                        </td>

                        <td class="py-3">
                            <div class="flex flex-wrap gap-2 max-w-[320px]">
                                @forelse($cluster->screens->take(3) as $screen)
                                    <div 
                                        class="flex items-center gap-2 px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded-md cursor-pointer transition"
                                        onclick="window.location='{{ route('screens.show',$screen->id) }}'"
                                        title="IP: {{ $screen->ip ?? '-' }}">

                                        <i class="fa-solid fa-tv text-[10px] text-slate-500"></i>
                                        <span class="text-[11px] font-medium text-slate-700">
                                            {{ $screen->name }}
                                        </span>

                                        @if($screen->is_online ?? false)
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                        @endif
                                    </div>
                                @empty
                                    <span class="text-[11px] text-slate-400">No screens</span>
                                @endforelse

                                @if($cluster->screens->count() > 3)
                                    <span class="text-[11px] text-slate-500 px-2 py-1">
                                        +{{ $cluster->screens->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($cluster->is_active)
                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium text-[11px]">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 font-medium text-[11px]">
                                    Disabled
                                </span>
                            @endif
                        </td>

                        <td class="text-right pr-6">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('clusters.show',$cluster) }}"
                                   class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition"
                                   title="View">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>

                                <a href="{{ route('clusters.edit',$cluster) }}"
                                   class="p-2 rounded-lg hover:bg-indigo-50 text-indigo-600 transition"
                                   title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>

                                <button type="button"
                                        onclick="confirmClusterDelete('{{ route('clusters.destroy', $cluster) }}', '{{ addslashes($cluster->name) }}')"
                                        class="p-2 rounded-lg hover:bg-red-50 text-red-600 transition"
                                        title="Delete">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-16 text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fa-solid fa-layer-group text-2xl"></i>
                                <span>No clusters found</span>
                                <a href="{{ route('clusters.create') }}" class="text-xs text-indigo-600 hover:underline">
                                    Create your first cluster
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3 border-t bg-slate-50">
            {{ $clusters->links() }}
        </div>

    </div>
</div>

<div id="deleteClusterModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md p-5 transform transition-all space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                <i class="fa-solid fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Delete Cluster Group</h3>
                <p class="text-xs text-slate-500 mt-0.5">Are you sure you want to permanently delete <span id="deleteClusterName" class="font-medium text-slate-800"></span>? Screens inside this cluster will be ungrouped but won't be deleted.</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="closeClusterDeleteModal()" class="px-3 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-medium transition">
                Cancel
            </button>
            <form id="deleteClusterForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-xs font-medium transition">
                    Delete Cluster
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle Auto-fading Flash Messages
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById('flash-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        }
    });

    // Custom Modal Controls
    function confirmClusterDelete(url, name) {
        document.getElementById('deleteClusterName').innerText = name;
        document.getElementById('deleteClusterForm').setAttribute('action', url);
        document.getElementById('deleteClusterModal').classList.remove('hidden');
    }

    function closeClusterDeleteModal() {
        document.getElementById('deleteClusterModal').classList.add('hidden');
    }
</script>

@endsection

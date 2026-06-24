@extends('layouts.app')

@section('header','Playlists')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    @if(session('success'))
        <div id="flash-alert" class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-check-circle mr-2"></i>
            <div>
                <span class="font-semibold">Success!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-alert" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-exclamation-circle mr-2"></i>
            <div>
                <span class="font-semibold">Alert!</span> {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">

        <div class="px-5 py-4 border-b flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Playlists</h2>
                <p class="text-xs text-slate-500">Manage content playlists</p>
            </div>

            <a href="{{ route('playlists.create') }}"
               class="bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Playlist
            </a>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Playlist</th>
                        <th class="text-left">Created</th>
                        <th class="text-right pr-5">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($playlists as $playlist)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-xs">
                                    🎬
                                </div>
                                <div>
                                    <div class="font-medium text-slate-800">
                                        {{ $playlist->name }}
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $playlist->items_count ?? 0 }} items
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="text-slate-500">
                            {{ $playlist->created_at->diffForHumans() }}
                        </td>

                        <td class="text-right pr-5">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('playlists.show',$playlist) }}"
                                   class="p-2 rounded hover:bg-blue-50 text-blue-600 transition"
                                   title="View">
                                    <i class="fa fa-eye text-xs"></i>
                                </a>

                                <a href="{{ route('playlists.edit',$playlist) }}"
                                   class="p-2 rounded hover:bg-indigo-50 text-indigo-600 transition"
                                   title="Edit">
                                    <i class="fa fa-pen text-xs"></i>
                                </a>

                                <button type="button" 
                                        onclick="confirmPlaylistDelete('{{ route('playlists.destroy', $playlist) }}', '{{ addslashes($playlist->name) }}')"
                                        class="p-2 rounded hover:bg-red-50 text-red-600 transition"
                                        title="Delete">
                                    <i class="fa fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-14 text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <div class="text-2xl">📺</div>
                                <span>No playlists yet</span>
                            </div>
                            <a href="{{ route('playlists.create') }}"
                               class="inline-block mt-3 bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                Create Playlist
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>


        @if($playlists->hasPages())
            <div class="px-5 py-3 border-t bg-slate-50">
                {{ $playlists->links() }}
            </div>
        @endif

    </div>
</div>

<div id="deletePlaylistModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md p-5 transform transition-all space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Delete Playlist</h3>
                <p class="text-xs text-slate-500 mt-0.5">Are you sure you want to permanently delete <span id="deletePlaylistName" class="font-medium text-slate-800"></span>? This will unassign it from any active screen schedules.</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="closePlaylistDeleteModal()" class="px-3 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-medium transition">
                Cancel
            </button>
            <form id="deletePlaylistForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-xs font-medium transition">
                    Delete Playlist
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
    function confirmPlaylistDelete(url, name) {
        document.getElementById('deletePlaylistName').innerText = name;
        document.getElementById('deletePlaylistForm').setAttribute('action', url);
        document.getElementById('deletePlaylistModal').classList.remove('hidden');
    }

    function closePlaylistDeleteModal() {
        document.getElementById('deletePlaylistModal').classList.add('hidden');
    }
</script>

@endsection
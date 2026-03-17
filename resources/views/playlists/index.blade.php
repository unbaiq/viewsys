@extends('layouts.app')

@section('header','Playlists')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Playlists</h1>
            <p class="text-sm text-gray-500 mt-1">
                Manage content playlists for your screens
            </p>
        </div>

        <a href="{{ route('playlists.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>

            Create Playlist
        </a>
    </div>


    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Table Header --}}
        <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">

            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-gray-800">
                    All Playlists
                </h2>

                <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                    {{ $playlists->total() }}
                </span>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Playlist</th>
                        <th class="px-6 py-4 text-left font-semibold">Created</th>
                        <th class="px-6 py-4 text-right font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($playlists as $playlist)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                    🎬
                                </div>

                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ $playlist->name }}
                                    </div>

                                    <div class="text-xs text-gray-400">
                                        {{ $playlist->items_count ?? 0 }} media items
                                    </div>
                                </div>

                            </div>

                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $playlist->created_at->diffForHumans() }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end items-center gap-3 text-sm">

                                <a href="{{ route('playlists.show',$playlist) }}"
                                   class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 font-medium">
                                    View
                                </a>

                                <a href="{{ route('playlists.edit',$playlist) }}"
                                   class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('playlists.destroy',$playlist) }}"
                                      onsubmit="return confirm('Delete playlist?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-medium">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="3">

                            <div class="py-20 text-center">

                                <div class="text-5xl mb-4">📺</div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                    No playlists yet
                                </h3>

                                <p class="text-sm text-gray-500 mb-6">
                                    Create your first playlist to start displaying content.
                                </p>

                                <a href="{{ route('playlists.create') }}"
                                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-semibold">

                                    Create Playlist

                                </a>

                            </div>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($playlists->hasPages())
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $playlists->links() }}
        </div>
        @endif

    </div>

</div>

@endsection
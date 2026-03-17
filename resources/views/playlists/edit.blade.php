@extends('layouts.app')

@section('header','Edit Playlist')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-500">
        <a href="{{ route('playlists.index') }}" class="hover:text-gray-700">Playlists</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700 font-medium">Edit</span>
    </div>


    {{-- Page Heading --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Playlist</h1>
            <p class="text-sm text-gray-500 mt-1">
                Update playlist details and manage your screen content.
            </p>
        </div>

        <div class="text-xs text-gray-400">
            Created {{ $playlist->created_at->diffForHumans() }}
        </div>
    </div>


    {{-- Card --}}
    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl">

        <div class="p-6">

            <form method="POST" action="{{ route('playlists.update',$playlist) }}" class="space-y-6">

                @csrf
                @method('PUT')

                {{-- Playlist Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Playlist Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $playlist->name) }}"
                        required
                        placeholder="Example: Office Lobby Screens"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                    @error('name')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Playlist Info --}}
                <div class="bg-gray-50 border rounded-xl p-4 text-sm text-gray-600 flex justify-between">
                    <span>
                        Media Items
                    </span>

                    <span class="font-semibold text-gray-800">
                        {{ $playlist->items()->count() }}
                    </span>
                </div>


                {{-- Actions --}}
                <div class="flex items-center justify-between pt-4 border-t">

                    <a href="{{ route('playlists.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>

                    <button
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Danger Zone --}}
    <div class="bg-white border border-red-100 rounded-2xl shadow-sm">

        <div class="p-6 flex items-center justify-between">

            <div>
                <h3 class="text-sm font-semibold text-red-600">
                    Delete Playlist
                </h3>

                <p class="text-xs text-gray-500 mt-1">
                    This action cannot be undone.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('playlists.destroy',$playlist) }}"
                  onsubmit="return confirm('Delete this playlist?')">

                @csrf
                @method('DELETE')

                <button
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Delete
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
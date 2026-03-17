@extends('layouts.app')

@section('header','Create Playlist')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Playlist</h1>
        <p class="text-sm text-gray-500 mt-1">
            Create a playlist to organize and display media on your screens.
        </p>
    </div>

    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl">
        <div class="p-6">

            <form method="POST" action="{{ route('playlists.store') }}" class="space-y-5">
                @csrf


                {{-- Company (Superadmin only) --}}
                @if(auth()->user()->role === 'superadmin')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Company
                    </label>

                    <select name="company_id"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">

                        <option value="">Select Company</option>

                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">
                                {{ $company->name }}
                            </option>
                        @endforeach

                    </select>
                </div>
                @endif


                {{-- Playlist Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Playlist Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Example: Office Lobby Screen"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex items-center justify-between pt-2">

                    <a href="{{ route('playlists.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>

                    <button
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold">

                        Create Playlist

                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
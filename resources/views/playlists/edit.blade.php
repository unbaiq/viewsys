@extends('layouts.app')

@section('header','Edit Playlist')

@section('content')

<div class="max-w-8xl mx-auto space-y-5">

<!-- ================= HEADER ================= -->
<div class="flex items-center justify-between">

    <div>
        <div class="text-xs text-slate-500">
            <a href="{{ route('playlists.index') }}" class="hover:text-slate-700">Playlists</a>
            <span class="mx-1">/</span>
            <span class="text-slate-700">Edit</span>
        </div>

        <div class="text-base font-semibold text-slate-900 mt-1">
            Edit Playlist
        </div>

        <div class="text-[11px] text-slate-500">
            Created {{ $playlist->created_at->diffForHumans() }}
        </div>
    </div>

</div>


<!-- ================= FORM ================= -->
<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<form method="POST" action="{{ route('playlists.update',$playlist) }}" class="p-5 space-y-4">
@csrf
@method('PUT')

<!-- NAME -->
<div>
    <label class="text-xs font-medium text-slate-600">Playlist Name</label>

    <input type="text"
        name="name"
        value="{{ old('name',$playlist->name) }}"
        required
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">

    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>


<!-- INFO -->
<div class="flex justify-between items-center text-xs bg-slate-50 border rounded-lg px-3 py-2">

    <span class="text-slate-500">Media Items</span>

    <span class="font-medium text-slate-800">
        {{ $playlist->items()->count() }}
    </span>

</div>


<!-- ACTIONS -->
<div class="flex justify-end gap-2 pt-4 border-t">

    <a href="{{ route('playlists.index') }}"
       class="px-3 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        Cancel
    </a>

    <button
        class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Save
    </button>

</div>

</form>

</div>


<!-- ================= DANGER ================= -->
<div class="bg-white border border-red-200 rounded-xl">

<div class="px-5 py-4 flex items-center justify-between">

    <div>
        <div class="text-xs font-medium text-red-600">
            Delete Playlist
        </div>

        <div class="text-[11px] text-slate-500">
            This action cannot be undone
        </div>
    </div>

    <form method="POST"
          action="{{ route('playlists.destroy',$playlist) }}"
          onsubmit="return confirm('Delete this playlist?')">
        @csrf
        @method('DELETE')

        <button
            class="px-4 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700">
            Delete
        </button>
    </form>

</div>

</div>

</div>

@endsection
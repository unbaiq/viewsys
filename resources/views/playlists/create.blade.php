@extends('layouts.app')

@section('header','Create Playlist')

@section('content')

<div class="max-w-8xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Create Playlist</h2>
    <p class="text-xs text-slate-500">Organize media for screens</p>
</div>

<!-- FORM -->
<form method="POST" action="{{ route('playlists.store') }}" class="p-5 space-y-4">
@csrf

{{-- COMPANY --}}
@if(auth()->user()->role === 'superadmin')
<div>
    <label class="text-xs font-medium text-slate-600">Company</label>

    <select name="company_id"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('company_id') border-red-500 @enderror">

        <option value="">Select Company</option>

        @foreach($companies as $company)
            <option value="{{ $company->id }}"
                {{ old('company_id')==$company->id?'selected':'' }}>
                {{ $company->name }}
            </option>
        @endforeach

    </select>

    @error('company_id')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
@endif


{{-- NAME --}}
<div>
    <label class="text-xs font-medium text-slate-600">Playlist Name</label>

    <input type="text"
        name="name"
        value="{{ old('name') }}"
        placeholder="Office Lobby Screen"
        required
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">

    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>


<!-- ACTIONS -->
<div class="flex justify-end gap-2 pt-4 border-t">

    <a href="{{ route('playlists.index') }}"
       class="px-3 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        Cancel
    </a>

    <button
        class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Create
    </button>

</div>

</form>

</div>

</div>

@endsection
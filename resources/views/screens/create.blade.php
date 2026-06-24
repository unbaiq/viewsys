@extends('layouts.app')

@section('header','Add Screen')

@section('content')

<div class="max-w-8xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Add Screen</h2>
    <p class="text-xs text-slate-500">Register a new display device</p>
</div>

<!-- FORM -->
<form method="POST" action="{{ route('screens.store') }}" class="p-5">
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- NAME -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Screen Name</label>
    <input type="text" name="name"
        value="{{ old('name') }}"
        required
        placeholder="Reception TV"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">

    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- DEVICE ID -->
<div>
    <label class="text-xs font-medium text-slate-600">Device ID</label>
    <input type="text" name="device_id"
        value="{{ old('device_id') }}"
        placeholder="Auto generate"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

    <p class="text-[11px] text-slate-500 mt-1">
        Leave empty to auto-generate
    </p>
</div>

<!-- ORIENTATION -->
<div>
    <label class="text-xs font-medium text-slate-600">Orientation</label>
    <select name="orientation"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        <option value="landscape" {{ old('orientation','landscape')=='landscape'?'selected':'' }}>Landscape</option>
        <option value="portrait" {{ old('orientation')=='portrait'?'selected':'' }}>Portrait</option>

    </select>
</div>

<!-- COMPANY -->
@if(auth()->user()->role === 'superadmin')
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Company</label>
    <select name="company_id"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('company_id') border-red-500 @enderror">

        <option value="">Select Company</option>

        @foreach($companies as $id => $name)
            <option value="{{ $id }}" {{ old('company_id')==$id?'selected':'' }}>
                {{ $name }}
            </option>
        @endforeach

    </select>

    @error('company_id')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
@else
<input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
@endif

<!-- LOCATION -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Location</label>
    <input type="text" name="location"
        value="{{ old('location') }}"
        placeholder="Lobby / Store Entrance"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

</div>

<!-- STATUS -->
@if(auth()->user()->role !== 'manager')
<div class="flex items-center justify-between mt-5 border-t pt-4">

    <label class="flex items-center gap-2 text-xs text-slate-600">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" name="status" value="1"
            {{ old('status',1) ? 'checked' : '' }}
            class="rounded border-slate-300">
        Active Screen
    </label>

@endif

<!-- ACTIONS -->
<div class="flex justify-end gap-2 {{ auth()->user()->role !== 'manager' ? '' : 'mt-5 border-t pt-4' }}">

    <a href="{{ route('screens.index') }}"
       class="px-3 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        Cancel
    </a>

    <button type="submit"
        class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Create
    </button>

</div>

@if(auth()->user()->role !== 'manager')
</div>
@endif

</form>

</div>

</div>

@endsection
@extends('layouts.app')

@section('header','Edit Screen')

@section('content')

<div class="max-w-8xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Edit Screen</h2>
    <p class="text-xs text-slate-500">Update screen configuration</p>
</div>

<!-- FORM -->
<form method="POST" action="{{ route('screens.update',$screen) }}" class="p-5">
@csrf
@method('PUT')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- NAME -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Screen Name</label>
    <input type="text" name="name"
        value="{{ old('name',$screen->name) }}"
        required
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">

    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- DEVICE ID -->
<div>
    <label class="text-xs font-medium text-slate-600">Device ID</label>
    <input type="text" name="device_id"
        value="{{ old('device_id',$screen->device_id) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

    <p class="text-[11px] text-slate-500 mt-1">
        Unique identifier for device connection
    </p>
</div>

<!-- ORIENTATION -->
<div>
    <label class="text-xs font-medium text-slate-600">Orientation</label>
    <select name="orientation"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        <option value="landscape" {{ old('orientation',$screen->orientation)=='landscape'?'selected':'' }}>Landscape</option>
        <option value="portrait" {{ old('orientation',$screen->orientation)=='portrait'?'selected':'' }}>Portrait</option>

    </select>
</div>

<!-- COMPANY -->
@if(auth()->user()->role === 'superadmin')
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Company</label>
    <select name="company_id"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        <option value="">Select Company</option>

        @foreach($companies as $id => $name)
            <option value="{{ $id }}"
                {{ old('company_id',$screen->company_id)==$id?'selected':'' }}>
                {{ $name }}
            </option>
        @endforeach

    </select>
</div>
@else
<input type="hidden" name="company_id" value="{{ $screen->company_id }}">

<div class="md:col-span-2 bg-slate-50 rounded-lg px-4 py-3">
    <div class="text-xs text-slate-500">Company</div>
    <div class="text-sm font-medium text-slate-800">
        {{ $screen->company->name ?? '-' }}
    </div>
</div>
@endif

<!-- LOCATION -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Location</label>
    <input type="text" name="location"
        value="{{ old('location',$screen->location) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- STATUS -->
<div>
    <label class="text-xs font-medium text-slate-600">Status</label>
    <select name="status"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        <option value="1" {{ old('status',$screen->status)=='1'?'selected':'' }}>Active</option>
        <option value="0" {{ old('status',$screen->status)=='0'?'selected':'' }}>Disabled</option>

    </select>
</div>

</div>

<!-- ACTIONS -->
<div class="flex justify-end gap-2 mt-5 border-t pt-4">

    <a href="{{ route('screens.index') }}"
       class="px-3 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        Cancel
    </a>

    <button type="submit"
        class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Update
    </button>

</div>

</form>

</div>

</div>

@endsection
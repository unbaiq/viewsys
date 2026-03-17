@extends('layouts.app')

@section('header','Edit Screen')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center">
<div class="w-full max-w-2xl bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

<!-- HEADER -->
<div class="text-center mb-8">
<h2 class="text-2xl font-bold text-gray-800">
Edit Screen
</h2>

<p class="text-sm text-gray-500 mt-1">
Update screen details and device configuration.
</p>
</div>


{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
{{ session('success') }}
</div>
@endif


{{-- VALIDATION ERRORS --}}
@if ($errors->any())
<div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
<ul class="text-sm text-red-600 space-y-1">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif


<form method="POST" action="{{ route('screens.update',$screen) }}" class="space-y-6">
@csrf
@method('PUT')


<!-- SCREEN NAME -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Screen Name
</label>

<input
type="text"
name="name"
value="{{ old('name',$screen->name) }}"
required
class="w-full border @error('name') border-red-300 @else border-gray-200 @enderror
focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">
</div>


<!-- DEVICE ID -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Device ID <span class="text-gray-400">(optional)</span>
</label>

<input
type="text"
name="device_id"
value="{{ old('device_id',$screen->device_id) }}"
placeholder="Leave empty to auto generate"
class="w-full border @error('device_id') border-red-300 @else border-gray-200 @enderror
focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<p class="text-xs text-gray-400 mt-1">
Unique identifier used by the screen to connect.
</p>
</div>


<!-- COMPANY -->
@if(auth()->user()->role === 'superadmin')

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Company
</label>

<select
name="company_id"
required
class="w-full border @error('company_id') border-red-300 @else border-gray-200 @enderror
focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<option value="">Select Company</option>

@foreach($companies as $id => $name)
<option value="{{ $id }}"
{{ old('company_id',$screen->company_id) == $id ? 'selected' : '' }}>
{{ $name }}
</option>
@endforeach

</select>
</div>

@else

<input type="hidden" name="company_id" value="{{ $screen->company_id }}">

<div class="bg-gray-50 rounded-xl p-4">
<div class="text-xs text-gray-500">Company</div>
<div class="font-semibold text-gray-800 mt-1">
{{ $screen->company->name ?? '' }}
</div>
</div>

@endif


<!-- LOCATION -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Location
</label>

<input
type="text"
name="location"
value="{{ old('location',$screen->location) }}"
placeholder="Lobby / Store Entrance / Office"
class="w-full border border-gray-200
focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">
</div>


<!-- ORIENTATION -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Screen Orientation
</label>

<select
name="orientation"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<option value="landscape"
{{ old('orientation',$screen->orientation) == 'landscape' ? 'selected' : '' }}>
Landscape
</option>

<option value="portrait"
{{ old('orientation',$screen->orientation) == 'portrait' ? 'selected' : '' }}>
Portrait
</option>

</select>
</div>


<!-- STATUS -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Status
</label>

<select
name="status"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<option value="1" {{ old('status',$screen->status) == 1 ? 'selected' : '' }}>
Active
</option>

<option value="0" {{ old('status',$screen->status) == 0 ? 'selected' : '' }}>
Disabled
</option>

</select>
</div>


<!-- ACTIONS -->
<div class="flex items-center justify-between pt-4">

<a href="{{ route('screens.index') }}"
class="text-sm text-gray-500 hover:text-gray-700">
Cancel
</a>

<button
type="submit"
class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg shadow-sm transition">
Update Screen
</button>

</div>

</form>

</div>
</div>

@endsection
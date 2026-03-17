@extends('layouts.app')

@section('header','Add Screen')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center">
<div class="w-full max-w-2xl bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

<!-- HEADER -->
<div class="text-center mb-8">
<h2 class="text-2xl font-bold text-gray-800">
Add New Screen
</h2>

<p class="text-sm text-gray-500 mt-1">
Register a new display device for your digital signage network.
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


<form method="POST" action="{{ route('screens.store') }}" class="space-y-6">
@csrf


<!-- SCREEN NAME -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Screen Name
</label>

<input
type="text"
name="name"
value="{{ old('name') }}"
required
placeholder="Reception TV / Store Display"
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
value="{{ old('device_id') }}"
placeholder="Leave empty to auto generate"
class="w-full border @error('device_id') border-red-300 @else border-gray-200 @enderror
focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<p class="text-xs text-gray-400 mt-1">
If left empty, the system will automatically generate a unique device ID.
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

<option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>
{{ $name }}
</option>

@endforeach

</select>
</div>

@else

<input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">

@endif


<!-- LOCATION -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Location
</label>

<input
type="text"
name="location"
value="{{ old('location') }}"
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

<option value="landscape" {{ old('orientation','landscape')=='landscape' ? 'selected':'' }}>
Landscape
</option>

<option value="portrait" {{ old('orientation')=='portrait' ? 'selected':'' }}>
Portrait
</option>

</select>
</div>


<!-- STATUS (OPTIONAL) -->
@if(auth()->user()->role !== 'manager')

<div>
<label class="flex items-center gap-2 text-sm text-gray-700">
<input type="checkbox" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
Active Screen
</label>
</div>

@endif


<!-- ACTIONS -->
<div class="flex items-center justify-between pt-4">

<a href="{{ route('screens.index') }}"
class="text-sm text-gray-500 hover:text-gray-700">
Cancel
</a>

<button
type="submit"
class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg shadow-sm transition">
Create Screen
</button>

</div>

</form>

</div>
</div>

@endsection
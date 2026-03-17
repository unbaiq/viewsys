@extends('layouts.app')

@section('header','Edit Cluster')

@section('content')

<div class="max-w-7xl mx-auto p-6">

@if ($errors->any())
<div class="mb-4 p-4 rounded bg-red-50 border border-red-200 text-red-700">
<ul class="list-disc ml-5 text-sm">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST" action="{{ route('clusters.update',$cluster) }}" class="space-y-8">
@csrf
@method('PUT')

<div class="grid md:grid-cols-2 gap-6">

<div>
<label class="text-sm font-medium">Cluster Name</label>
<input name="name"
value="{{ old('name',$cluster->name) }}"
required
class="border rounded-lg p-2 w-full mt-1">
</div>

<div>
<label class="text-sm font-medium">Location</label>
<input name="location"
value="{{ old('location',$cluster->location) }}"
class="border rounded-lg p-2 w-full mt-1">
</div>

</div>

<div>
<label>Description</label>
<textarea name="description"
class="border rounded-lg p-2 w-full mt-1">{{ old('description',$cluster->description) }}</textarea>
</div>

{{-- Layouts --}}
<div>

<label class="block font-medium mb-4">
Screen Layout
</label>

@php
$layout = old('layout', $cluster->type ?? 'fullscreen');
@endphp

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">

<label class="cursor-pointer">
<input type="radio" name="layout" value="fullscreen"
class="peer hidden layoutRadio"
{{ $layout=='fullscreen'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video bg-gray-200 rounded mb-2"></div>
<p class="text-center text-sm">Full Screen</p>
</div>
</label>

<label class="cursor-pointer">
<input type="radio" name="layout" value="half"
class="peer hidden layoutRadio"
{{ $layout=='half'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video flex rounded overflow-hidden mb-2">
<div class="w-1/2 bg-gray-300"></div>
<div class="w-1/2 bg-gray-400"></div>
</div>
<p class="text-center text-sm">Half Split</p>
</div>
</label>

<label class="cursor-pointer">
<input type="radio" name="layout" value="sidebar"
class="peer hidden layoutRadio"
{{ $layout=='sidebar'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video flex rounded overflow-hidden mb-2">
<div class="w-3/4 bg-gray-300"></div>
<div class="w-1/4 bg-gray-500"></div>
</div>
<p class="text-center text-sm">Sidebar</p>
</div>
</label>

<label class="cursor-pointer">
<input type="radio" name="layout" value="ticker"
class="peer hidden layoutRadio"
{{ $layout=='ticker'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video flex flex-col rounded overflow-hidden mb-2">
<div class="flex-1 bg-gray-300"></div>
<div class="h-6 bg-gray-600"></div>
</div>
<p class="text-center text-sm">Ticker</p>
</div>
</label>

<label class="cursor-pointer">
<input type="radio" name="layout" value="grid"
class="peer hidden layoutRadio"
{{ $layout=='grid'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video grid grid-cols-2 gap-1 mb-2">
<div class="bg-gray-300"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-300"></div>
</div>
<p class="text-center text-sm">4 Grid</p>
</div>
</label>

<label class="cursor-pointer">
<input type="radio" name="layout" value="header"
class="peer hidden layoutRadio"
{{ $layout=='header'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video flex flex-col mb-2">
<div class="h-6 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>
<p class="text-center text-sm">Header</p>
</div>
</label>

<label class="cursor-pointer">
<input type="radio" name="layout" value="triple"
class="peer hidden layoutRadio"
{{ $layout=='triple'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video flex mb-2">
<div class="w-1/3 bg-gray-300"></div>
<div class="w-1/3 bg-gray-400"></div>
<div class="w-1/3 bg-gray-500"></div>
</div>
<p class="text-center text-sm">Triple</p>
</div>
</label>

<label class="cursor-pointer">
<input type="radio" name="layout" value="menu"
class="peer hidden layoutRadio"
{{ $layout=='menu'?'checked':'' }}>
<div class="border rounded-xl p-3 peer-checked:border-blue-600 peer-checked:ring-2">
<div class="aspect-video flex mb-2">
<div class="w-1/4 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>
<p class="text-center text-sm">Menu Board</p>
</div>
</label>

</div>

@error('layout')
<p class="text-red-500 text-sm mt-2">{{ $message }}</p>
@enderror

</div>

{{-- Header Text --}}
<div id="headerField" style="display:none;">
<label class="block text-sm font-medium mb-1">Header Text</label>

<input
type="text"
name="header_text"
value="{{ old('header_text',$cluster->header_text) }}"
class="border rounded-lg p-2 w-full"
placeholder="Welcome message">
</div>

{{-- Ticker Text --}}
<div id="tickerField" style="display:none;">

<label class="block text-sm font-medium mb-1">Ticker Text</label>

<textarea
name="ticker_text"
rows="3"
class="border rounded-lg p-2 w-full"
placeholder="Sale Today | Offers | Announcement">{{ old('ticker_text',$cluster->ticker_text) }}</textarea>

</div>

{{-- Screens --}}
<div>

<label class="block font-medium mb-2">
Assign Screens
</label>

@php
$selected = old('screens',$assignedScreens ?? []);
@endphp

<select name="screens[]" multiple
class="border rounded-lg p-3 w-full h-40">

@foreach($screens as $screen)

<option value="{{ $screen->id }}"
{{ in_array($screen->id,$selected) ? 'selected' : '' }}>
{{ $screen->name }}
</option>

@endforeach

</select>

</div>

{{-- Active --}}
<div class="flex items-center gap-2">

<input type="checkbox"
name="is_active"
value="1"
{{ old('is_active',$cluster->is_active) ? 'checked' : '' }}>

<label class="text-sm">Active</label>

</div>

<div>
<button class="bg-blue-600 text-white px-6 py-3 rounded-lg">
Update Cluster
</button>
</div>

</form>

</div>

<script>

function toggleLayoutFields(){

let layout = document.querySelector('input[name="layout"]:checked')?.value;

let header = document.getElementById('headerField');
let ticker = document.getElementById('tickerField');

if(!header || !ticker) return;

header.style.display='none';
ticker.style.display='none';

if(layout === 'header'){
header.style.display='block';
}

if(layout === 'ticker'){
ticker.style.display='block';
}

}

document.querySelectorAll('.layoutRadio').forEach(el=>{
el.addEventListener('change',toggleLayoutFields);
});

document.addEventListener('DOMContentLoaded', toggleLayoutFields);

</script>

@endsection
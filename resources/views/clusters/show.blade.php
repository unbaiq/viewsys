@extends('layouts.app')

@section('header','Cluster Details')

@section('content')

<div class="max-w-7xl mx-auto p-6 space-y-6">

{{-- Cluster Info --}}
<div class="bg-white border rounded-xl p-6">

<h2 class="text-lg font-semibold mb-4">
Cluster Information
</h2>

<div class="grid md:grid-cols-2 gap-4">

<div>
<label class="text-sm text-gray-500">Name</label>
<p class="font-medium">{{ $cluster->name }}</p>
</div>

<div>
<label class="text-sm text-gray-500">Location</label>
<p>{{ $cluster->location ?? '—' }}</p>
</div>

<div>
<label class="text-sm text-gray-500">Description</label>
<p>{{ $cluster->description ?? '—' }}</p>
</div>

<div>
<label class="text-sm text-gray-500">Status</label>

@if($cluster->is_active)
<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
Active
</span>
@else
<span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">
Inactive
</span>
@endif

</div>

</div>

</div>

{{-- Layout Preview --}}
<div class="bg-white border rounded-xl p-6">

<h2 class="text-lg font-semibold mb-4">
Screen Layout
</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">

@php
$layout = $cluster->type;
@endphp

<label class="cursor-default">
<div class="border rounded-xl p-3 {{ $layout=='fullscreen'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video bg-gray-200 rounded mb-2"></div>
<p class="text-center text-sm">Full Screen</p>
</div>
</label>

<label>
<div class="border rounded-xl p-3 {{ $layout=='half'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video flex rounded overflow-hidden mb-2">
<div class="w-1/2 bg-gray-300"></div>
<div class="w-1/2 bg-gray-400"></div>
</div>
<p class="text-center text-sm">Half Split</p>
</div>
</label>

<label>
<div class="border rounded-xl p-3 {{ $layout=='sidebar'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video flex rounded overflow-hidden mb-2">
<div class="w-3/4 bg-gray-300"></div>
<div class="w-1/4 bg-gray-500"></div>
</div>
<p class="text-center text-sm">Sidebar</p>
</div>
</label>

<label>
<div class="border rounded-xl p-3 {{ $layout=='ticker'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video flex flex-col rounded overflow-hidden mb-2">
<div class="flex-1 bg-gray-300"></div>
<div class="h-6 bg-gray-600"></div>
</div>
<p class="text-center text-sm">Ticker</p>
</div>
</label>

<label>
<div class="border rounded-xl p-3 {{ $layout=='grid'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video grid grid-cols-2 gap-1 mb-2">
<div class="bg-gray-300"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-300"></div>
</div>
<p class="text-center text-sm">4 Grid</p>
</div>
</label>

<label>
<div class="border rounded-xl p-3 {{ $layout=='header'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video flex flex-col mb-2">
<div class="h-6 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>
<p class="text-center text-sm">Header</p>
</div>
</label>

<label>
<div class="border rounded-xl p-3 {{ $layout=='triple'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video flex mb-2">
<div class="w-1/3 bg-gray-300"></div>
<div class="w-1/3 bg-gray-400"></div>
<div class="w-1/3 bg-gray-500"></div>
</div>
<p class="text-center text-sm">Triple</p>
</div>
</label>

<label>
<div class="border rounded-xl p-3 {{ $layout=='menu'?'border-blue-600 ring-2':'' }}">
<div class="aspect-video flex mb-2">
<div class="w-1/4 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>
<p class="text-center text-sm">Menu Board</p>
</div>
</label>

</div>

</div>

{{-- Header Text --}}
@if($cluster->type === 'header')
<div class="bg-white border rounded-xl p-6">
<h3 class="font-semibold mb-2">Header Text</h3>
<p>{{ $cluster->header_text }}</p>
</div>
@endif

{{-- Ticker Text --}}
@if($cluster->type === 'ticker')
<div class="bg-white border rounded-xl p-6">
<h3 class="font-semibold mb-2">Ticker Text</h3>
<p>{{ $cluster->ticker_text }}</p>
</div>
@endif

{{-- Screens --}}
<div class="bg-white border rounded-xl p-6">

<h2 class="text-lg font-semibold mb-4">
Assigned Screens
</h2>

@if($cluster->screens->count())

<div class="grid md:grid-cols-3 gap-3">

@foreach($cluster->screens as $screen)

<div class="border rounded-lg p-3">
{{ $screen->name }}
</div>

@endforeach

</div>

@else

<p class="text-gray-500 text-sm">
No screens assigned.
</p>

@endif

</div>

<div class="flex gap-3">

<a href="{{ route('clusters.edit',$cluster) }}"
class="px-5 py-2 bg-blue-600 text-white rounded-lg">
Edit
</a>

<a href="{{ route('clusters.index') }}"
class="px-5 py-2 border rounded-lg">
Back
</a>

</div>

</div>

@endsection
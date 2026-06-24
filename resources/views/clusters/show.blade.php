@extends('layouts.app')

@section('header','Cluster Details')

@section('content')

<div class="h-full overflow-y-auto px-3 sm:px-4 md:px-6 py-4">

<div class="max-w-6xl mx-auto space-y-4">

{{-- Cluster Info --}}
<div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">

<div class="flex items-center justify-between mb-3">
    <h2 class="text-sm font-semibold text-slate-900">Cluster Information</h2>

    <span class="px-2 py-1 text-[11px] rounded 
        {{ $cluster->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
        {{ $cluster->is_active ? 'Active' : 'Inactive' }}
    </span>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">

<div>
<label class="text-slate-500">Name</label>
<p class="font-medium text-slate-800">{{ $cluster->name }}</p>
</div>

<div>
<label class="text-slate-500">Location</label>
<p class="text-slate-700">{{ $cluster->location ?? '—' }}</p>
</div>

<div class="sm:col-span-2">
<label class="text-slate-500">Description</label>
<p class="text-slate-700">{{ $cluster->description ?? '—' }}</p>
</div>

</div>

</div>


{{-- Layout Preview --}}
<div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">

<h2 class="text-sm font-semibold text-slate-900 mb-3">
Screen Layout
</h2>

@php
$layout = $cluster->type;
@endphp

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">

@foreach([
'fullscreen'=>'Full',
'half'=>'Half',
'sidebar'=>'Sidebar',
'ticker'=>'Ticker',
'grid'=>'Grid',
'header'=>'Header',
'triple'=>'Triple',
'menu'=>'Menu'
] as $key=>$label)

<div class="border rounded-lg p-2 
    {{ $layout==$key ? 'border-indigo-600 ring-1' : 'border-slate-200' }}">

<div class="aspect-video rounded mb-1 overflow-hidden">

@if($key=='fullscreen')
<div class="bg-gray-300 w-full h-full"></div>

@elseif($key=='half')
<div class="flex h-full">
<div class="w-1/2 bg-gray-300"></div>
<div class="w-1/2 bg-gray-400"></div>
</div>

@elseif($key=='sidebar')
<div class="flex h-full">
<div class="w-3/4 bg-gray-300"></div>
<div class="w-1/4 bg-gray-500"></div>
</div>

@elseif($key=='ticker')
<div class="flex flex-col h-full">
<div class="flex-1 bg-gray-300"></div>
<div class="h-4 bg-gray-600"></div>
</div>

@elseif($key=='grid')
<div class="grid grid-cols-2 gap-1 h-full">
<div class="bg-gray-300"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-300"></div>
</div>

@elseif($key=='header')
<div class="flex flex-col h-full">
<div class="h-4 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>

@elseif($key=='triple')
<div class="flex h-full">
<div class="w-1/3 bg-gray-300"></div>
<div class="w-1/3 bg-gray-400"></div>
<div class="w-1/3 bg-gray-500"></div>
</div>

@elseif($key=='menu')
<div class="flex h-full">
<div class="w-1/4 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>
@endif

</div>

<p class="text-center text-[11px] text-slate-600">{{ $label }}</p>

</div>

@endforeach

</div>

</div>


{{-- Header Text --}}
@if($cluster->type === 'header')
<div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">
<h3 class="text-xs font-semibold text-slate-700 mb-1">Header Text</h3>
<p class="text-sm text-slate-800">{{ $cluster->header_text }}</p>
</div>
@endif


{{-- Ticker Text --}}
@if($cluster->type === 'ticker')
<div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">
<h3 class="text-xs font-semibold text-slate-700 mb-1">Ticker Text</h3>
<p class="text-sm text-slate-800">{{ $cluster->ticker_text }}</p>
</div>
@endif


{{-- Screens --}}
<div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">

<h2 class="text-sm font-semibold text-slate-900 mb-3">
Assigned Screens
</h2>

@if($cluster->screens->count())

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 text-xs">

@foreach($cluster->screens as $screen)

<div class="border border-slate-200 rounded-md px-2 py-2 text-slate-700 bg-slate-50">
{{ $screen->name }}
</div>

@endforeach

</div>

@else

<p class="text-slate-500 text-xs">
No screens assigned.
</p>

@endif

</div>


{{-- ACTIONS --}}
<div class="flex flex-col sm:flex-row justify-end gap-2">

<a href="{{ route('clusters.index') }}"
class="px-3 py-1.5 text-xs border border-slate-200 rounded-md text-slate-600 hover:bg-slate-50">
Back
</a>

<a href="{{ route('clusters.edit',$cluster) }}"
class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
Edit
</a>

</div>

</div>

</div>

@endsection
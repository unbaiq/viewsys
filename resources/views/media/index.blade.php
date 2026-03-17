@extends('layouts.app')

@section('header','Media Library')

@section('content')

<div class="space-y-6">


<!-- HEADER BAR -->
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">

<form class="flex items-center gap-3">

<input
name="search"
value="{{ request('search') }}"
placeholder="Search media..."
class="w-64 border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none rounded-lg px-4 py-2 text-sm">

<button
class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-lg text-sm transition">
Search
</button>

@if(request('search'))
<a href="{{ route('media.index') }}"
class="text-sm text-gray-500 hover:text-gray-700">
Reset
</a>
@endif

</form>


<a href="{{ route('media.create') }}"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-sm">
+ Upload Media
</a>

</div>



<!-- MEDIA GRID -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">

@forelse($media as $item)

<div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden group hover:shadow-md transition">

<!-- MEDIA PREVIEW -->
<div class="h-40 bg-gray-100 relative flex items-center justify-center overflow-hidden">

@if($item->type === 'image')

<img
src="{{ asset('storage/'.$item->file_path) }}"
class="object-cover h-full w-full group-hover:scale-105 transition">

@else

<video class="h-full w-full object-cover">
<source src="{{ asset('storage/'.$item->file_path) }}">
</video>

@endif


<!-- TYPE BADGE -->
<span class="absolute top-2 left-2 text-[10px] px-2 py-1 rounded bg-black/70 text-white uppercase">
{{ $item->type }}
</span>

</div>


<!-- INFO -->
<div class="p-3">

<div class="text-sm font-semibold text-gray-800 truncate">
{{ $item->name }}
</div>

<div class="text-xs text-gray-400 mt-1">
{{ number_format($item->size/1024/1024,2) }} MB
</div>


<!-- ACTIONS -->
<div class="flex justify-between items-center mt-3 text-xs">

<a
href="{{ route('media.show',$item) }}"
class="text-indigo-600 hover:text-indigo-800 font-medium">
View
</a>

<form method="POST"
action="{{ route('media.destroy',$item) }}"
onsubmit="return confirm('Delete this media?')">
@csrf
@method('DELETE')

<button class="text-red-500 hover:text-red-700 font-medium">
Delete
</button>

</form>

</div>

</div>

</div>

@empty

<div class="col-span-full">

<div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm">

<div class="text-gray-400 text-sm">
No media uploaded yet
</div>

<p class="text-xs text-gray-400 mt-2">
Upload images or videos to start creating digital signage content.
</p>

<a href="{{ route('media.create') }}"
class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2 rounded-lg">
Upload First Media
</a>

</div>

</div>

@endforelse

</div>



<!-- PAGINATION -->
@if($media->hasPages())
<div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
{{ $media->links() }}
</div>
@endif


</div>

@endsection
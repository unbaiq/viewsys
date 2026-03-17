@extends('layouts.app')

@section('header',$playlist->name)

@section('content')

<div class="grid md:grid-cols-2 gap-6">

<!-- Playlist Media -->
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-800 mb-4">
Playlist Media
</h3>

@if($playlist->items->count())

@foreach($playlist->items as $item)

<div class="flex items-center justify-between border-b py-3">

<div>
<div class="font-medium text-gray-800">
{{ $item->media->name }}
</div>

<div class="text-xs text-gray-500">
{{ ucfirst($item->media->type) }}
</div>
</div>

<form method="POST"
action="{{ route('playlist-items.destroy',$item) }}">
@csrf
@method('DELETE')

<button class="text-red-600 text-sm">
Remove
</button>

</form>

</div>

@endforeach

@else

<p class="text-sm text-gray-500">
No media added yet
</p>

@endif

</div>

<!-- Add Media -->

<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

<h3 class="font-semibold text-gray-800 mb-4">
Add Media
</h3>

<div class="space-y-3 max-h-[400px] overflow-y-auto">

@foreach($media as $item)

<form method="POST"
action="{{ route('playlists.addMedia',$playlist) }}"
class="flex items-center justify-between border rounded-lg px-3 py-2">

@csrf

<div>
<div class="font-medium text-sm">
{{ $item->name }}
</div>

<div class="text-xs text-gray-500">
{{ ucfirst($item->type) }}
</div>
</div>

<input type="hidden" name="media_id" value="{{ $item->id }}">

<button class="text-indigo-600 text-sm font-medium">
Add
</button>

</form>

@endforeach

</div>

</div>

</div>

@endsection
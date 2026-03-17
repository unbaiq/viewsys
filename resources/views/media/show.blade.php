@extends('layouts.app')

@section('header','Media Details')

@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6">

@if($media->type === 'image')

<img
src="{{ asset('storage/'.$media->file_path) }}"
class="rounded-lg mb-6">

@else

<video controls class="w-full rounded-lg mb-6">
<source src="{{ asset('storage/'.$media->file_path) }}">
</video>

@endif

<h2 class="text-lg font-semibold">
{{ $media->name }}
</h2>

<p class="text-sm text-gray-500 mt-1">
{{ number_format($media->size/1024/1024,2) }} MB
</p>

</div>

@endsection
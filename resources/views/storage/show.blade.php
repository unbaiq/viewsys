@extends('layouts.app')

@section('header','Storage Details')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center">

<div class="w-full max-w-3xl bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

<!-- HEADER -->
<div class="flex items-start justify-between mb-6">

<div>
<h2 class="text-2xl font-bold text-gray-800">
{{ optional($storage->company)->name ?? 'No Company Assigned' }}
</h2>

<p class="text-sm text-gray-500">
Storage usage and limits
</p>
</div>

</div>


<!-- STATS -->
<div class="grid grid-cols-3 gap-6 mb-8">

<div class="bg-gray-50 rounded-xl p-4">
<div class="text-xs text-gray-500">Used Storage</div>
<div class="text-lg font-semibold text-gray-800 mt-1">
{{ number_format($storage->used) }} MB
</div>
</div>

<div class="bg-gray-50 rounded-xl p-4">
<div class="text-xs text-gray-500">Storage Limit</div>
<div class="text-lg font-semibold text-gray-800 mt-1">
{{ number_format($storage->limit) }} MB
</div>
</div>

<div class="bg-gray-50 rounded-xl p-4">
<div class="text-xs text-gray-500">Usage</div>
<div class="text-lg font-semibold text-gray-800 mt-1">
{{ $storage->percentUsed() }}%
</div>
</div>

</div>


<!-- PROGRESS -->
<div class="mb-8">

<div class="flex justify-between text-sm text-gray-600 mb-2">
<span>Storage Usage</span>
<span>{{ $storage->percentUsed() }}%</span>
</div>

<div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">

<div
class="h-3 rounded-full
@if($storage->percentUsed() > 90)
bg-red-500
@elseif($storage->percentUsed() > 75)
bg-yellow-500
@else
bg-indigo-600
@endif
"
style="width: {{ $storage->percentUsed() }}%">
</div>

</div>

</div>


<!-- EXTRA INFO -->
<div class="bg-gray-50 rounded-xl p-4 mb-6">

<div class="text-sm text-gray-600">
Remaining Storage
</div>

<div class="font-semibold text-gray-800 mt-1">
{{ number_format($storage->remaining()) }} MB left
</div>

</div>


<!-- ACTIONS -->
<div class="flex items-center justify-between border-t pt-6">

<a href="{{ route('storage-usage.index') }}"
class="text-sm text-gray-500 hover:text-gray-700">
← Back to Storage
</a>

<div class="flex gap-3">





</div>

</div>

</div>

</div>

@endsection
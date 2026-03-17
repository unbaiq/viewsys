@extends('layouts.app')

@section('header','Edit Storage')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center">

<div class="w-full max-w-xl bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

<!-- HEADER -->
<div class="text-center mb-8">

<h2 class="text-2xl font-bold text-gray-800">
Edit Storage Limit
</h2>

<p class="text-sm text-gray-500 mt-1">
Update storage capacity for this company.
</p>

</div>


<form method="POST" action="{{ route('storage-usage.update',$storage) }}" class="space-y-6">
@csrf
@method('PUT')


<!-- COMPANY INFO -->
<div class="bg-gray-50 rounded-xl p-4">

<div class="text-xs text-gray-500">
Company
</div>

<div class="font-semibold text-gray-800 mt-1">
{{ $storage->company->name }}
</div>

</div>


<!-- STORAGE LIMIT -->
<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
Storage Limit (MB)
</label>

<input
name="limit"
value="{{ old('limit',$storage->limit) }}"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<p class="text-xs text-gray-400 mt-1">
Example: 10240 MB = 10 GB
</p>

</div>


<!-- ACTIONS -->
<div class="flex items-center justify-between pt-4">

<a href="{{ route('storage.index') }}"
class="text-sm text-gray-500 hover:text-gray-700">
Cancel
</a>

<button
class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg shadow-sm transition">
Update Storage
</button>

</div>

</form>

</div>

</div>

@endsection
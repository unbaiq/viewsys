@extends('layouts.app')

@section('header','Add storage-usage')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center">

<div class="w-full max-w-xl bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

<!-- TITLE -->
<div class="text-center mb-8">

<h2 class="text-2xl font-bold text-gray-800">
Add storage-usage Limit
</h2>

<p class="text-sm text-gray-500 mt-1">
Assign storage-usage capacity to a company.
</p>

</div>


<form method="POST" action="{{ route('storage-usage.store') }}" class="space-y-6">
@csrf


<!-- COMPANY -->
<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
Company
</label>

<select
name="company_id"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

@foreach($companies as $id => $name)

<option value="{{ $id }}">
{{ $name }}
</option>

@endforeach

</select>

</div>


<!-- STORAGE LIMIT -->
<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
Storage Limit (MB)
</label>

<input
name="limit"
value="10240"
placeholder="Enter storage limit"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<p class="text-xs text-gray-400 mt-1">
Example: 10240 MB = 10 GB
</p>

</div>


<!-- ACTIONS -->
<div class="flex items-center justify-between pt-4">

<a href="{{ route('storage-usage.index') }}"
class="text-sm text-gray-500 hover:text-gray-700">
Cancel
</a>

<button
class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg shadow-sm transition">
Save storage-usage
</button>

</div>

</form>

</div>

</div>

@endsection
@extends('layouts.app')

@section('header','Storage Management')

@section('content')

<div class="bg-white shadow-sm rounded-2xl border border-gray-100">

{{-- FILTER BAR --}}
<div class="p-6 border-b bg-gray-50 rounded-t-2xl">

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

<form class="flex flex-wrap items-center gap-3">

<input
name="search"
value="{{ request('search') }}"
placeholder="Search company..."
class="border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none rounded-lg px-4 py-2 text-sm w-56">

<select name="filter"
class="border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-3 py-2 text-sm">

<option value="">All Usage</option>
<option value="warning" {{ request('filter')=='warning' ? 'selected' : '' }}>
Above 80%
</option>

</select>

<button
class="bg-gray-900 hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition">
Filter
</button>

@if(request()->hasAny(['search','filter']))
<a href="{{ route('storage-usage.index') }}"
class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-lg transition">
Reset
</a>
@endif

</form>

<a href="{{ route('storage-usage.create') }}"
class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2 rounded-lg shadow-sm transition">
+ Add Storage
</a>

</div>

</div>


{{-- TABLE --}}
<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600">

<tr>
<th class="px-6 py-4 text-left font-semibold">Company</th>
<th class="px-6 py-4 text-left font-semibold">Used</th>
<th class="px-6 py-4 text-left font-semibold">Limit</th>
<th class="px-6 py-4 text-left font-semibold">Usage</th>
<th class="px-6 py-4 text-right font-semibold">Actions</th>
</tr>

</thead>

<tbody class="divide-y divide-gray-100">

@forelse($storages as $storage)

<tr class="hover:bg-gray-50 transition">

<td class="px-6 py-4 font-semibold text-gray-800">
{{ $storage->company->name }}
</td>


<td class="px-6 py-4 text-gray-700">
{{ number_format($storage->used) }} MB
</td>


<td class="px-6 py-4 text-gray-700">
{{ number_format($storage->limit) }} MB
</td>


<td class="px-6 py-4">

<div class="flex items-center gap-3">

<div class="w-40 bg-gray-200 rounded-full h-2 overflow-hidden">

<div
class="h-2 rounded-full
@if($storage->usage_percent > 90)
bg-red-500
@elseif($storage->usage_percent > 75)
bg-yellow-500
@else
bg-indigo-600
@endif
"
style="width: {{ $storage->usage_percent }}%">
</div>

</div>

<span class="text-xs text-gray-500">
{{ $storage->usage_percent }}%
</span>

</div>

</td>


<td class="px-6 py-4 text-right">

<div class="flex justify-end gap-4 text-sm">

<a href="{{ route('storage-usage.show',$storage) }}"
class="text-blue-600 hover:text-blue-800 font-medium">
View
</a>

<a href="{{ route('storage-usage.edit',$storage) }}"
class="text-indigo-600 hover:text-indigo-800 font-medium">
Edit
</a>

<form method="POST"
action="{{ route('storage-usage.destroy',$storage) }}"
onsubmit="return confirm('Delete storage record?')">

@csrf
@method('DELETE')

<button class="text-red-600 hover:text-red-800 font-medium">
Delete
</button>

</form>

</div>

</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center py-12 text-gray-500">
No storage records found.
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


{{-- PAGINATION --}}
@if($storages->hasPages())
<div class="p-6 border-t bg-gray-50 rounded-b-2xl">
{{ $storages->links() }}
</div>
@endif

</div>

@endsection
@extends('layouts.app')

@section('header','Screens')

@section('content')

<div class="bg-white shadow-sm rounded-2xl border border-gray-100">

<!-- Header -->
<div class="p-6 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <!-- Filter -->
    <form method="GET" class="flex flex-wrap items-center gap-3">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search screen..."
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">

        <select name="status"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">

            <option value="">All Status</option>

            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                Online
            </option>

            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                Offline
            </option>

        </select>

        <button type="submit"
            class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-lg text-sm font-medium">
            Filter
        </button>

        @if(request()->has('search') || request()->has('status'))
        <a href="{{ route('screens.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">
            Reset
        </a>
        @endif

    </form>

    <!-- Add Button -->
    <a href="{{ route('screens.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-sm">
        + Add Screen
    </a>

</div>


<!-- Table -->
<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600">
<tr>
<th class="p-4 text-left font-medium">Screen</th>
<th class="text-left font-medium">Company</th>
<th class="text-left font-medium">Status</th>
<th class="text-left font-medium">Last Seen</th>
<th class="text-right pr-6 font-medium">Actions</th>
</tr>
</thead>

<tbody class="divide-y">

@forelse($screens as $screen)

<tr class="hover:bg-gray-50 transition">

<!-- Screen -->
<td class="p-4">
<div class="font-semibold text-gray-800">
{{ $screen->name }}
</div>

<div class="text-xs text-gray-500">
{{ $screen->device_id }}
</div>
</td>

<!-- Company -->
<td class="text-gray-700">
{{ $screen->company->name ?? '-' }}
</td>

<!-- Status -->
<td>

@if($screen->isOnline())

<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
Online
</span>

@else

<span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
Offline
</span>

@endif

</td>

<!-- Last Seen -->
<td class="text-gray-600">

@if($screen->last_seen)
{{ $screen->last_seen->diffForHumans() }}
@else
-
@endif

</td>

<!-- Actions -->
<td class="text-right pr-6">

<div class="flex justify-end gap-4 text-sm">

<a href="{{ route('screens.show',$screen) }}"
class="text-blue-600 hover:underline">
View
</a>

<a href="{{ route('screens.edit',$screen) }}"
class="text-indigo-600 hover:underline">
Edit
</a>

<form method="POST"
action="{{ route('screens.destroy',$screen) }}"
onsubmit="return confirm('Delete this screen?')">

@csrf
@method('DELETE')

<button type="submit"
class="text-red-600 hover:underline">
Delete
</button>

</form>

</div>

</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center py-10 text-gray-500">
No screens found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


<!-- Pagination -->
<div class="p-6 border-t">
{{ $screens->withQueryString()->links() }}
</div>

</div>

@endsection
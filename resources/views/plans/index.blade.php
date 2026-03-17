@extends('layouts.app')

@section('header','Subscription Plans')

@section('content')

<div class="bg-white shadow-sm rounded-2xl border border-gray-100">

{{-- FILTER BAR --}}
<div class="p-6 border-b bg-gray-50 rounded-t-2xl">

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

<form class="flex flex-wrap items-center gap-3">

<input
name="search"
value="{{ request('search') }}"
placeholder="Search plan..."
class="border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none rounded-lg px-4 py-2 text-sm w-56">

<select name="status"
class="border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-3 py-2 text-sm">

<option value="">All Status</option>
<option value="1" {{ request('status')=='1' ? 'selected' : '' }}>Active</option>
<option value="0" {{ request('status')=='0' ? 'selected' : '' }}>Disabled</option>

</select>

<button
class="bg-gray-900 hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition">
Filter
</button>

@if(request()->hasAny(['search','status']))
<a href="{{ route('plans.index') }}"
class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-lg transition">
Reset
</a>
@endif

</form>

<a href="{{ route('plans.create') }}"
class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2 rounded-lg shadow-sm transition">
+ Create Plan
</a>

</div>

</div>


{{-- TABLE --}}
<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600">

<tr>
<th class="px-6 py-4 text-left font-semibold">Plan</th>
<th class="px-6 py-4 text-left font-semibold">Price</th>
<th class="px-6 py-4 text-left font-semibold">Screens</th>
<th class="px-6 py-4 text-left font-semibold">Storage</th>
<th class="px-6 py-4 text-left font-semibold">Status</th>
<th class="px-6 py-4 text-right font-semibold">Actions</th>
</tr>

</thead>

<tbody class="divide-y divide-gray-100">

@forelse($plans as $plan)

<tr class="hover:bg-gray-50 transition">

<td class="px-6 py-4">

<div class="font-semibold text-gray-800">
{{ $plan->name }}
</div>

<div class="text-xs text-gray-500">
{{ count($plan->features ?? []) }} features
</div>

</td>


<td class="px-6 py-4 text-gray-700 font-medium">
₹{{ number_format($plan->price) }}
</td>


<td class="px-6 py-4 text-gray-700">
{{ $plan->screen_limit }}
</td>


<td class="px-6 py-4 text-gray-700">
{{ $plan->storage_limit }} MB
</td>


<td class="px-6 py-4">

<form method="POST" action="{{ route('plans.toggle', $plan) }}">
@csrf

<button>

@if($plan->is_active)

<span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
Active
</span>

@else

<span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-full">
Disabled
</span>

@endif

</button>

</form>

</td>


<td class="px-6 py-4 text-right">

<div class="flex justify-end gap-4 text-sm">

<a href="{{ route('plans.show',$plan) }}"
class="text-blue-600 hover:text-blue-800 font-medium">
View
</a>

<a href="{{ route('plans.edit',$plan) }}"
class="text-indigo-600 hover:text-indigo-800 font-medium">
Edit
</a>

<form method="POST" action="{{ route('plans.destroy',$plan) }}"
onsubmit="return confirm('Delete plan?')">

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
<td colspan="6" class="text-center py-10 text-gray-500">
No plans found.
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


{{-- PAGINATION --}}
@if($plans->hasPages())
<div class="p-6 border-t bg-gray-50 rounded-b-2xl">
{{ $plans->links() }}
</div>
@endif


</div>

@endsection
@extends('layouts.app')

@section('header','Create Plan')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center">

<div class="w-full max-w-2xl bg-white shadow-sm border border-gray-100 rounded-2xl p-8">

<!-- TITLE -->
<div class="mb-8 text-center">
<h2 class="text-2xl font-bold text-gray-800">
Create New Plan
</h2>
<p class="text-sm text-gray-500 mt-1">
Define pricing, limits, and features for your ViewSys subscription plan.
</p>
</div>


<form method="POST" action="{{ route('plans.store') }}" class="space-y-6">
@csrf


<!-- PLAN NAME -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Plan Name
</label>

<input
name="name"
placeholder="Starter / Business / Enterprise"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">
</div>


<!-- PRICE -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Monthly Price
</label>

<input
name="price"
placeholder="99"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">
</div>


<!-- LIMITS -->
<div class="grid grid-cols-2 gap-4">

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Screen Limit
</label>

<input
name="screen_limit"
value="5"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">
</div>


<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Storage Limit (MB)
</label>

<input
name="storage_limit"
value="10240"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">
</div>

</div>


<!-- FEATURES -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Features
</label>

<input
name="features"
placeholder="analytics, priority support, api"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<p class="text-xs text-gray-400 mt-1">
Separate features with commas.
</p>
</div>


<!-- STATUS -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Status
</label>

<select
name="is_active"
class="w-full border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg px-4 py-2 outline-none">

<option value="1">Active</option>
<option value="0">Disabled</option>

</select>
</div>


<!-- ACTIONS -->
<div class="flex items-center justify-between pt-4">

<a href="{{ route('plans.index') }}"
class="text-sm text-gray-500 hover:text-gray-700">
Cancel
</a>

<button
class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg shadow-sm transition">
Create Plan
</button>

</div>


</form>

</div>

</div>

@endsection
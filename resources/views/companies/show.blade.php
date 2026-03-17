@extends('layouts.app')

@section('header','Company Details')

@section('content')

<div class="max-w-2xl mx-auto bg-white shadow rounded-xl p-8">

<h2 class="text-2xl font-semibold mb-6">
{{ $company->name }}
</h2>

<div class="grid gap-4 text-sm">

<div class="flex justify-between border-b pb-2">
<span class="text-gray-500">Email</span>
<span>{{ $company->email }}</span>
</div>

<div class="flex justify-between border-b pb-2">
<span class="text-gray-500">Phone</span>
<span>{{ $company->phone }}</span>
</div>

<div class="flex justify-between border-b pb-2">
<span class="text-gray-500">Plan</span>
<span class="capitalize">{{ $company->plan }}</span>
</div>

<div class="flex justify-between border-b pb-2">
<span class="text-gray-500">Screen Limit</span>
<span>{{ $company->screen_limit }}</span>
</div>

<div class="flex justify-between border-b pb-2">
<span class="text-gray-500">Storage Limit</span>
<span>{{ $company->storage_limit }} MB</span>
</div>

<div class="flex justify-between border-b pb-2">
<span class="text-gray-500">Status</span>

@if($company->is_active)
<span class="text-green-600">Active</span>
@else
<span class="text-red-500">Inactive</span>
@endif

</div>

<div class="flex justify-between">
<span class="text-gray-500">Created</span>
<span>{{ $company->created_at->format('d M Y') }}</span>
</div>

</div>

<div class="flex gap-3 mt-8">

<a href="{{ route('companies.edit',$company) }}"
class="bg-indigo-600 text-white px-5 py-2 rounded-lg">
Edit
</a>

<a href="{{ route('companies.index') }}"
class="border px-5 py-2 rounded-lg">
Back
</a>

</div>

</div>

@endsection
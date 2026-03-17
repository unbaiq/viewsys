@extends('layouts.app')

@section('header','Edit Company')

@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">

<h2 class="text-xl font-semibold mb-6">
Edit Company
</h2>

<form method="POST"
action="{{ route('companies.update',$company) }}">

@csrf
@method('PUT')

<div class="grid gap-5">

<div>
<label class="text-sm font-medium">Company Name</label>
<input name="name"
value="{{ $company->name }}"
class="w-full border rounded-lg px-3 py-2 mt-1">
</div>

<div>
<label class="text-sm font-medium">Plan</label>

<select name="plan"
class="w-full border rounded-lg px-3 py-2 mt-1">

<option {{ $company->plan=='starter'?'selected':'' }}>starter</option>
<option {{ $company->plan=='business'?'selected':'' }}>business</option>
<option {{ $company->plan=='enterprise'?'selected':'' }}>enterprise</option>

</select>

</div>

<div class="grid grid-cols-2 gap-4">

<div>
<label>Screen Limit</label>
<input name="screen_limit"
value="{{ $company->screen_limit }}"
class="w-full border rounded-lg px-3 py-2">
</div>

<div>
<label>Storage Limit</label>
<input name="storage_limit"
value="{{ $company->storage_limit }}"
class="w-full border rounded-lg px-3 py-2">
</div>

</div>

<div>
<label>Status</label>

<select name="is_active"
class="w-full border rounded-lg px-3 py-2">

<option value="1" {{ $company->is_active ? 'selected':'' }}>
Active
</option>

<option value="0" {{ !$company->is_active ? 'selected':'' }}>
Disabled
</option>

</select>

</div>

<div class="flex gap-3 pt-4">

<button class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
Update
</button>

<a href="{{ route('companies.index') }}"
class="px-6 py-2 border rounded-lg">
Cancel
</a>

</div>

</div>

</form>

</div>

@endsection
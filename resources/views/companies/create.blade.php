@extends('layouts.app')

@section('header','Create Company')

@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">

<h2 class="text-xl font-semibold mb-6">Create Company</h2>

@if ($errors->any())
<div class="mb-4 text-red-600 text-sm">
@foreach ($errors->all() as $error)
<div>{{ $error }}</div>
@endforeach
</div>
@endif

<form method="POST" action="{{ route('companies.store') }}">
@csrf

<div class="grid gap-5">

<div>
<label class="text-sm font-medium">Company Name</label>
<input name="name"
value="{{ old('name') }}"
class="w-full border rounded-lg px-3 py-2 mt-1">
</div>

<div>
<label class="text-sm font-medium">Owner</label>
<select name="user_id" class="w-full border rounded-lg px-3 py-2 mt-1">

@foreach($users as $user)
<option value="{{ $user->id }}">
{{ $user->name }}
</option>
@endforeach

</select>
</div>

<div class="grid grid-cols-2 gap-4">

<div>
<label class="text-sm font-medium">Email</label>
<input name="email"
value="{{ old('email') }}"
class="w-full border rounded-lg px-3 py-2 mt-1">
</div>

<div>
<label class="text-sm font-medium">Phone</label>
<input name="phone"
value="{{ old('phone') }}"
class="w-full border rounded-lg px-3 py-2 mt-1">
</div>

</div>

<div>
<label class="text-sm font-medium">Plan</label>

<select name="plan" class="w-full border rounded-lg px-3 py-2 mt-1">
<option value="starter">Starter</option>
<option value="business">Business</option>
<option value="enterprise">Enterprise</option>
</select>

</div>

<div class="grid grid-cols-2 gap-4">

<div>
<label class="text-sm font-medium">Screen Limit</label>
<input name="screen_limit"
value="5"
class="w-full border rounded-lg px-3 py-2 mt-1">
</div>

<div>
<label class="text-sm font-medium">Storage Limit (MB)</label>
<input name="storage_limit"
value="10240"
class="w-full border rounded-lg px-3 py-2 mt-1">
</div>

</div>

<div class="flex gap-3 pt-4">

<button class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
Create Company
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
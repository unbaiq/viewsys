@extends('layouts.app')

@section('header','Create User')

@section('content')

<div class="max-w-3xl mx-auto">

<div class="bg-white shadow rounded-xl">

<!-- Header -->
<div class="p-6 border-b">
<h2 class="text-lg font-semibold text-gray-800">
New User
</h2>
<p class="text-sm text-gray-500">
Create a new platform user and assign their role.
</p>
</div>

<!-- Form -->
<form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-6">
@csrf


<!-- Name -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Full Name
</label>

<input
type="text"
name="name"
value="{{ old('name') }}"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

@error('name')
<p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror
</div>


<!-- Email -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Email Address
</label>

<input
type="email"
name="email"
value="{{ old('email') }}"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

@error('email')
<p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror
</div>


<!-- Password -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Password
</label>

<input
type="password"
name="password"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<p class="text-xs text-gray-500 mt-1">
Minimum 6 characters recommended.
</p>

@error('password')
<p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror
</div>


<!-- Role -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Role
</label>

<select name="role"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<option value="manager">Manager</option>
<option value="admin">Admin</option>
<option value="superadmin">Super Admin</option>

</select>
</div>


<!-- Company -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Company
</label>

<select name="company_id"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<option value="">None</option>

@foreach($companies as $id => $name)
<option value="{{ $id }}">
{{ $name }}
</option>
@endforeach

</select>
</div>


<!-- Screen -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Assigned Screen
</label>

<select name="screen_id"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<option value="">None</option>

@foreach($screens as $id => $name)
<option value="{{ $id }}">
{{ $name }}
</option>
@endforeach

</select>

<p class="text-xs text-gray-500 mt-1">
Managers can be limited to a single screen.
</p>

</div>


<!-- Status -->
<div>
<label class="flex items-center gap-2 text-sm text-gray-700">

<input
type="checkbox"
name="is_active"
value="1"
checked
class="rounded border-gray-300">

Active account

</label>
</div>


<!-- Buttons -->
<div class="flex justify-end gap-3 pt-4 border-t">

<a
href="{{ route('users.index') }}"
class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">
Cancel
</a>

<button
class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
Create User
</button>

</div>

</form>

</div>

</div>

@endsection
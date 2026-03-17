@extends('layouts.app')

@section('header','Edit User')

@section('content')

<div class="max-w-3xl mx-auto">

<div class="bg-white shadow rounded-xl">

<!-- Header -->
<div class="p-6 border-b">

<h2 class="text-lg font-semibold text-gray-800">
Edit User
</h2>

<p class="text-sm text-gray-500">
Update user details, permissions and assignments.
</p>

</div>


<form method="POST"
action="{{ route('users.update',$user) }}"
class="p-6 space-y-6">

@csrf
@method('PUT')


<!-- Name -->
<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
Full Name
</label>

<input
type="text"
name="name"
value="{{ old('name',$user->name) }}"
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
value="{{ old('email',$user->email) }}"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

@error('email')
<p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror

</div>


<!-- Password -->
<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
New Password
</label>

<input
type="password"
name="password"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<p class="text-xs text-gray-500 mt-1">
Leave blank if you don't want to change the password.
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

<select
name="role"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>
Super Admin
</option>

<option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
Admin
</option>

<option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>
Manager
</option>

</select>

</div>


<!-- Company -->
<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
Company
</label>

<select
name="company_id"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<option value="">None</option>

@foreach($companies as $id => $name)

<option value="{{ $id }}"
{{ $user->company_id == $id ? 'selected' : '' }}>
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

<select
name="screen_id"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

<option value="">None</option>

@foreach($screens as $id => $name)

<option value="{{ $id }}"
{{ $user->screen_id == $id ? 'selected' : '' }}>
{{ $name }}
</option>

@endforeach

</select>

<p class="text-xs text-gray-500 mt-1">
Managers can be limited to a specific screen.
</p>

</div>


<!-- Status -->
<div>

<label class="flex items-center gap-2 text-sm text-gray-700">

<input
type="checkbox"
name="is_active"
value="1"
{{ $user->is_active ? 'checked' : '' }}
class="rounded border-gray-300">

Active account

</label>

</div>


<!-- Actions -->
<div class="flex justify-end gap-3 pt-4 border-t">

<a
href="{{ route('users.index') }}"
class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">
Cancel
</a>

<button
class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
Update User
</button>

</div>

</form>

</div>

</div>

@endsection
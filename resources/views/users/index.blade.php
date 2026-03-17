@extends('layouts.app')

@section('header','Users')

@section('content')

<div class="bg-white rounded-xl shadow">

<!-- Top Bar -->
<div class="p-6 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4">

<!-- Filters -->
<form class="flex flex-wrap gap-3">

<input
name="search"
value="{{ request('search') }}"
placeholder="Search name or email..."
class="border rounded-lg px-3 py-2 text-sm w-56">

<select name="role" class="border rounded-lg px-3 py-2 text-sm">

<option value="">All Roles</option>
<option value="superadmin">Super Admin</option>
<option value="admin">Admin</option>
<option value="manager">Manager</option>

</select>

<select name="status" class="border rounded-lg px-3 py-2 text-sm">

<option value="">All Status</option>
<option value="1">Active</option>
<option value="0">Disabled</option>

</select>

<button class="bg-gray-900 text-white px-4 rounded-lg text-sm">
Filter
</button>

</form>


<a href="{{ route('users.create') }}"
class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
+ Create User
</a>

</div>


<!-- Table -->
<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600">

<tr>

<th class="p-4 text-left">User</th>

<th class="text-left">Role</th>

<th class="text-left">Company</th>

<th class="text-left">Screen</th>

<th class="text-left">Status</th>

<th class="text-right pr-6">Actions</th>

</tr>

</thead>


<tbody class="divide-y">

@forelse($users as $user)

<tr class="hover:bg-gray-50">

<td class="p-4">

<div class="flex items-center gap-3">

<img
class="w-9 h-9 rounded-full"
src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}">

<div>

<div class="font-medium text-gray-900">
{{ $user->name }}
</div>

<div class="text-xs text-gray-500">
{{ $user->email }}
</div>

</div>

</div>

</td>


<td class="capitalize text-gray-700">
{{ $user->role }}
</td>


<td class="text-gray-600">
{{ $user->company->name ?? '-' }}
</td>


<td class="text-gray-600">
{{ $user->screen->name ?? '-' }}
</td>


<td>

<form method="POST" action="{{ route('users.toggle',$user) }}">
@csrf

<button>

@if($user->is_active)

<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
Active
</span>

@else

<span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">
Disabled
</span>

@endif

</button>

</form>

</td>


<td class="text-right pr-6">

<div class="flex justify-end gap-3 text-sm">

<a
href="{{ route('users.show',$user) }}"
class="text-blue-600 hover:underline">
View
</a>

<a
href="{{ route('users.edit',$user) }}"
class="text-indigo-600 hover:underline">
Edit
</a>

<form method="POST"
action="{{ route('users.destroy',$user) }}">
@csrf
@method('DELETE')

<button class="text-red-600 hover:underline">
Delete
</button>

</form>

</div>

</td>

</tr>

@empty

<tr>
<td colspan="6" class="text-center py-10 text-gray-400">
No users found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


<!-- Pagination -->
<div class="p-6 border-t">
{{ $users->links() }}
</div>

</div>

@endsection
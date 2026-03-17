@extends('layouts.app')

@section('header','User Details')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

<!-- Profile Card -->
<div class="bg-white shadow rounded-xl p-6">

<div class="flex items-center gap-6">

<img
class="w-16 h-16 rounded-full"
src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128">

<div>
<h2 class="text-xl font-semibold text-gray-800">
{{ $user->name }}
</h2>

<p class="text-gray-500 text-sm">
{{ $user->email }}
</p>

<div class="mt-2 flex gap-2">

<span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-700 capitalize">
{{ $user->role }}
</span>

@if($user->is_active)
<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
Active
</span>
@else
<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
Disabled
</span>
@endif

</div>

</div>

</div>

</div>


<!-- User Information -->
<div class="bg-white shadow rounded-xl">

<div class="p-6 border-b">
<h3 class="font-semibold text-gray-800">
User Information
</h3>
</div>

<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

<div>
<label class="text-gray-500">Full Name</label>
<p class="font-medium text-gray-800">{{ $user->name }}</p>
</div>

<div>
<label class="text-gray-500">Email</label>
<p class="font-medium text-gray-800">{{ $user->email }}</p>
</div>

<div>
<label class="text-gray-500">Role</label>
<p class="font-medium capitalize text-gray-800">{{ $user->role }}</p>
</div>

<div>
<label class="text-gray-500">Status</label>
<p class="font-medium">
@if($user->is_active)
<span class="text-green-600">Active</span>
@else
<span class="text-red-600">Disabled</span>
@endif
</p>
</div>

<div>
<label class="text-gray-500">Company</label>
<p class="font-medium text-gray-800">
{{ $user->company->name ?? 'Not Assigned' }}
</p>
</div>

<div>
<label class="text-gray-500">Screen</label>
<p class="font-medium text-gray-800">
{{ $user->screen->name ?? 'Not Assigned' }}
</p>
</div>

<div>
<label class="text-gray-500">Created</label>
<p class="font-medium text-gray-800">
{{ $user->created_at->format('d M Y') }}
</p>
</div>

<div>
<label class="text-gray-500">Last Updated</label>
<p class="font-medium text-gray-800">
{{ $user->updated_at->diffForHumans() }}
</p>
</div>

</div>

</div>


<!-- Actions -->
<div class="bg-white shadow rounded-xl p-6 flex justify-between">

<a
href="{{ route('users.index') }}"
class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">
Back
</a>

<div class="flex gap-3">

<a
href="{{ route('users.edit',$user) }}"
class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
Edit
</a>

<form method="POST" action="{{ route('users.destroy',$user) }}">
@csrf
@method('DELETE')

<button
onclick="return confirm('Delete this user?')"
class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
Delete
</button>

</form>

</div>

</div>

</div>

@endsection
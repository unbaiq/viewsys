@extends('layouts.app')

@section('header','Edit User')

@section('content')

<div class="max-w-8xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Edit User</h2>
    <p class="text-xs text-slate-500">Update user details</p>
</div>

<form method="POST" action="{{ route('users.update',$user) }}" class="p-5">
@csrf
@method('PUT')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- NAME -->
<div>
    <label class="text-xs font-medium text-slate-600">Full Name</label>
    <input type="text" name="name"
        value="{{ old('name',$user->name) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- EMAIL -->
<div>
    <label class="text-xs font-medium text-slate-600">Email</label>
    <input type="email" name="email"
        value="{{ old('email',$user->email) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- PASSWORD -->
<div>
    <label class="text-xs font-medium text-slate-600">New Password</label>
    <input type="password" name="password"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

    <p class="text-[11px] text-slate-500 mt-1">
        Leave blank to keep current password
    </p>
</div>

<!-- ROLE -->
<div>
    <label class="text-xs font-medium text-slate-600">Role</label>

    @php
        $currentRole = old('role', $user->getRoleNames()->first());
    @endphp

    <select name="role" id="roleSelect"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        @foreach($roles as $role)
            <option value="{{ $role }}"
                {{ $currentRole == $role ? 'selected' : '' }}>
                {{ ucfirst($role) }}
            </option>
        @endforeach

    </select>
</div>

<!-- COMPANY -->
<div>
    <label class="text-xs font-medium text-slate-600">Company</label>
    <select name="company_id"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        <option value="">None</option>

        @foreach($companies as $id => $name)
            <option value="{{ $id }}"
                {{ old('company_id',$user->company_id)==$id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach

    </select>
</div>

<!-- SCREEN (ONLY FOR MANAGER) -->
<div id="screenField" class="md:col-span-2 hidden">
    <label class="text-xs font-medium text-slate-600">Assign Screen</label>
    <select name="screen_id"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        <option value="">None</option>

        @foreach($screens as $id => $name)
            <option value="{{ $id }}"
                {{ old('screen_id',$user->screen_id)==$id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach

    </select>
</div>

</div>

<!-- STATUS + ACTION -->
<div class="mt-5 flex items-center justify-between border-t pt-4">

    <!-- STATUS -->
    <label class="flex items-center gap-2 text-xs text-slate-600">
        <input type="hidden" name="is_active" value="0">

        <input type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active',$user->is_active) ? 'checked' : '' }}
            class="rounded border-slate-300">

        Active
    </label>

    <!-- BUTTONS -->
    <div class="flex gap-2">
        <a href="{{ route('users.index') }}"
           class="px-3 py-2 text-xs border rounded-lg text-slate-600 hover:bg-slate-50">
            Cancel
        </a>

        <button
            class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Update
        </button>
    </div>

</div>

</form>

</div>

</div>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const roleSelect = document.getElementById('roleSelect');
    const screenField = document.getElementById('screenField');

    function toggleScreenField() {
        if (roleSelect.value === 'manager') {
            screenField.classList.remove('hidden');
        } else {
            screenField.classList.add('hidden');
        }
    }

    // Initial load
    toggleScreenField();

    // On change
    roleSelect.addEventListener('change', toggleScreenField);
});
</script>

@endsection
@extends('layouts.app')

@section('header','Create User')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">

        <!-- HEADER -->
        <div class="px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-slate-800">Create User</h2>
            <p class="text-xs text-slate-500">Add a new user</p>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('users.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <!-- NAME -->
            <div>
                <label class="text-xs font-medium text-slate-600">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="text-xs font-medium text-slate-600">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-xs font-medium text-slate-600">Password</label>
                <input type="password" name="password"
                    class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- ROLE -->
            <div>
                <label class="text-xs font-medium text-slate-600">Role</label>

                @if(auth()->user()->isSuperAdmin())
                    <select name="role" id="roleSelect"
                        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
                        <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                        <option value="manager" {{ old('role')=='manager'?'selected':'' }}>Manager</option>
                    </select>

                @elseif(auth()->user()->isAdmin())
                    <select name="role" id="roleSelect"
                        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-gray-100 cursor-not-allowed">
                        <option value="manager" selected>Manager</option>
                    </select>
                @endif
            </div>

            <!-- COMPANY -->
            @if(auth()->user()->isSuperAdmin())
            <div>
                <label class="text-xs font-medium text-slate-600">Company</label>
                <select name="company_id"
                    class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

                    <option value="">Select Company</option>

                    @foreach($companies as $id => $name)
                        <option value="{{ $id }}" {{ old('company_id')==$id?'selected':'' }}>
                            {{ $name }}
                        </option>
                    @endforeach

                </select>
            </div>
            @else
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            @endif

            <!-- SCREEN (ONLY FOR MANAGER) -->
            <div id="screenField" class="hidden md:col-span-2">
                <label class="text-xs font-medium text-slate-600">Assign Screen</label>
                <select name="screen_id"
                    class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

                    <option value="">None</option>

                    @foreach($screens ?? [] as $id => $name)
                        <option value="{{ $id }}" {{ old('screen_id')==$id?'selected':'' }}>
                            {{ $name }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="mt-6 flex items-center justify-between border-t pt-4">

            <!-- STATUS -->
            <label class="flex items-center gap-2 text-xs text-slate-600">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active',1) ? 'checked' : '' }}
                    class="rounded border-slate-300">
                Active User
            </label>

            <!-- BUTTONS -->
            <div class="flex gap-3">
                <a href="{{ route('users.index') }}"
                   class="px-4 py-2 text-xs border rounded-lg text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>

                <button
                    class="px-5 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Create User
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
        if (roleSelect && roleSelect.value === 'manager') {
            screenField.classList.remove('hidden');
        } else {
            screenField.classList.add('hidden');
        }
    }

    // Initial load
    toggleScreenField();

    // Change event
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleScreenField);
    }
});
</script>

@endsection
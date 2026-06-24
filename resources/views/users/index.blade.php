@extends('layouts.app')

@section('header','Users')

@section('content')

<div class="max-w-8xl mx-auto space-y-4">

    @if(session('success'))
        <div id="flash-alert" class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-check-circle mr-2"></i>
            <div>
                <span class="font-semibold">Success!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-alert" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-exclamation-circle mr-2"></i>
            <div>
                <span class="font-semibold">Alert!</span> {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm px-5 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-base font-semibold text-slate-900">User Management</h2>
            <p class="text-xs text-slate-500">Manage users and permissions</p>
        </div>

        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fa fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                <input name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="pl-8 pr-3 py-2 w-48 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <select name="role" class="px-2 py-2 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">Role</option>
                @foreach(['superadmin','admin','manager'] as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="px-2 py-2 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500">
                <option value="">Status</option>
                <option value="1" {{ request('status')==='1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status')==='0' ? 'selected' : '' }}>Disabled</option>
            </select>

            <button class="bg-slate-900 text-white px-3 py-2 rounded-lg text-xs">Apply</button>
            <a href="{{ route('users.index') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-xs">Reset</a>
            <a href="{{ route('users.create') }}" class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-xs font-medium hover:bg-indigo-700">+ User</a>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto max-h-[70vh]">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 text-left">User</th>
                        <th class="text-left">Role</th>
                        <th class="text-left">Company</th>
                        <th class="text-left">Screen</th>
                        <th class="text-left">Status</th>
                        <th class="text-right pr-5">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($users as $user)
                    @php $role = $user->roles->first()->name ?? null; @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <img class="w-8 h-8 rounded-full border" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}">
                                <div>
                                    <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="px-2 py-0.5 text-[10px] rounded-full capitalize
                                {{ $role === 'superadmin' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $role === 'admin' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                {{ $role === 'manager' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ $role ?? '-' }}
                            </span>
                        </td>

                        <td class="text-slate-600">{{ optional($user->company)->name ?? '-' }}</td>

                        <td class="text-slate-600">
                            @if($user->isManager() && $user->screen)
                                <span class="px-2 py-0.5 text-[10px] bg-slate-100 text-slate-700 rounded-full">
                                    {{ $user->screen->name }}
                                </span>
                            @else - @endif
                        </td>

                        <td>
                            <form method="POST" action="{{ route('users.toggle',$user) }}">
                                @csrf
                                @method('PATCH')
                                <button>
                                    @if($user->is_active)
                                        <span class="px-2 py-0.5 text-[10px] bg-green-100 text-green-700 rounded-full">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] bg-red-100 text-red-600 rounded-full">Disabled</span>
                                    @endif
                                </button>
                            </form>
                        </td>

                        <td class="text-right pr-5">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('users.edit',$user) }}" class="p-1.5 hover:bg-indigo-50 text-indigo-600 rounded">
                                    <i class="fa fa-pen text-[10px]"></i>
                                </a>

                                <button type="button" 
                                        onclick="confirmDelete('{{ route('users.destroy', $user) }}', '{{ $user->name }}')" 
                                        class="p-1.5 hover:bg-red-50 text-red-600 rounded">
                                    <i class="fa fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-400 text-xs">No users found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-4 py-3 border-t bg-slate-50 text-xs">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md p-5 transform transition-all space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Delete User</h3>
                <p class="text-xs text-slate-500 mt-0.5">Are you sure you want to permanently delete <span id="deleteUserName" class="font-medium text-slate-800"></span>? This action cannot be undone.</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="px-3 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-medium transition">
                Cancel
            </button>
            <form id="deleteModalForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-xs font-medium transition">
                    Delete User
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle Auto-fading Flash Messages
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById('flash-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        }
    });

    // Custom Modal Controllers
    function confirmDelete(url, userName) {
        document.getElementById('deleteUserName').innerText = userName;
        document.getElementById('deleteModalForm').setAttribute('action', url);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>

@endsection
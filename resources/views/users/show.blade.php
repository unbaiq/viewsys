@extends('layouts.app')

@section('header','User Details')

@section('content')

<div class="max-w-8xl mx-auto space-y-5">

<!-- ================= PROFILE ================= -->
<div class="bg-white border border-slate-200 rounded-xl p-5 flex items-center justify-between">

    <div class="flex items-center gap-4">

        <img
            class="w-14 h-14 rounded-full border"
            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128">

        <div>
            <div class="text-base font-semibold text-slate-900">
                {{ $user->name }}
            </div>

            <div class="text-xs text-slate-500">
                {{ $user->email }}
            </div>

            <div class="flex items-center gap-2 mt-2">

                <!-- ROLE -->
                @php
                    $roleColors = [
                        'superadmin' => 'bg-purple-100 text-purple-700',
                        'admin' => 'bg-indigo-100 text-indigo-700',
                        'manager' => 'bg-blue-100 text-blue-700',
                    ];
                @endphp

                <span class="px-2.5 py-0.5 text-[11px] rounded-full font-medium capitalize {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $user->role }}
                </span>

                <!-- STATUS -->
                @if($user->is_active)
                    <span class="px-2.5 py-0.5 text-[11px] rounded-full bg-green-100 text-green-700 font-medium">
                        Active
                    </span>
                @else
                    <span class="px-2.5 py-0.5 text-[11px] rounded-full bg-red-100 text-red-700 font-medium">
                        Disabled
                    </span>
                @endif

            </div>
        </div>

    </div>

    <!-- QUICK ACTION -->
    <a href="{{ route('users.edit',$user) }}"
       class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Edit
    </a>

</div>


<!-- ================= DETAILS ================= -->
<div class="bg-white border border-slate-200 rounded-xl">

<div class="px-5 py-3 border-b text-sm font-medium text-slate-700">
    User Information
</div>

<div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">

    <div>
        <p class="text-xs text-slate-500">Full Name</p>
        <p class="font-medium text-slate-800">{{ $user->name }}</p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Email</p>
        <p class="font-medium text-slate-800">{{ $user->email }}</p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Role</p>
        <p class="font-medium capitalize text-slate-800">{{ $user->role }}</p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Company</p>
        <p class="font-medium text-slate-800">
            {{ $user->company->name ?? '-' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Screen</p>
        <p class="font-medium text-slate-800">
            {{ $user->screen->name ?? '-' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Status</p>
        <p class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
            {{ $user->is_active ? 'Active' : 'Disabled' }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Created</p>
        <p class="font-medium text-slate-800">
            {{ $user->created_at->format('d M Y') }}
        </p>
    </div>

    <div>
        <p class="text-xs text-slate-500">Updated</p>
        <p class="font-medium text-slate-800">
            {{ $user->updated_at->diffForHumans() }}
        </p>
    </div>

</div>

</div>


<!-- ================= ACTIONS ================= -->
<div class="flex justify-between items-center">

    <a href="{{ route('users.index') }}"
       class="px-4 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        ← Back
    </a>

    <form method="POST" action="{{ route('users.destroy',$user) }}">
        @csrf
        @method('DELETE')

        <button
            onclick="return confirm('Delete this user?')"
            class="px-4 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700">
            Delete
        </button>
    </form>

</div>

</div>

@endsection
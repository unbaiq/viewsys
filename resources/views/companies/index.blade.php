@extends('layouts.app')

@section('header','Companies')

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

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">

        <div class="px-5 py-4 border-b bg-slate-50 flex justify-between">

            <form class="flex gap-2">
                <input name="search" value="{{ request('search') }}"
                    placeholder="Search..."
                    class="border rounded-lg px-3 py-2 text-xs">

                <button class="bg-slate-900 text-white px-3 py-2 text-xs rounded-lg">
                    Apply
                </button>
            </form>

            <a href="{{ route('companies.create') }}"
               class="bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg">
                + Company
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left">Company</th>
                        <th>Plan</th>
                        <th>Screens</th>
                        <th>Storage</th>
                        <th>Start</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th class="text-right pr-5">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($companies as $company)

                    <tr class="hover:bg-slate-50">

                        <td class="px-5 py-3">
                            <div class="font-medium">{{ $company->name }}</div>
                            <div class="text-[11px] text-slate-500">{{ $company->email }}</div>
                        </td>

                        <td>{{ ucfirst($company->plan) }}</td>

                        <td>{{ $company->screen_limit }}</td>

                        <td>{{ $company->storage_limit }} MB</td>

                        <td>
                            {{ $company->plan_start_date ? $company->plan_start_date->format('d M Y') : '-' }}
                        </td>

                        <td>
                            @if($company->plan_end_date)
                                {{ $company->plan_end_date->format('d M Y') }}

                                @if($company->isExpired())
                                    <div class="text-red-500 text-[10px]">Expired</div>
                                @else
                                    <div class="text-green-600 text-[10px]">
                                        {{ $company->remainingDays() }} days left
                                    </div>
                                @endif
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            @if($company->is_active)
                                <span class="text-green-600">Active</span>
                            @else
                                <span class="text-red-500">Disabled</span>
                            @endif
                        </td>

                        <td class="text-right pr-5">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('companies.edit',$company) }}"
                                   class="text-indigo-600">
                                    <i class="fa fa-pen"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('companies.toggle',$company) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button class="{{ $company->is_active ? 'text-yellow-600' : 'text-green-600' }}">
                                        <i class="fa {{ $company->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('companies.destroy',$company) }}"
                                      onsubmit="return confirm('Delete company?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center py-10 text-slate-400">
                            No companies found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-4">
            {{ $companies->links() }}
        </div>

    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById('flash-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        }
    });
</script>

@endsection
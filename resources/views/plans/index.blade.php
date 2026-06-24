@extends('layouts.app')

@section('header','Subscription Plans')

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

        {{-- ================= FILTER BAR ================= --}}
        <div class="px-5 py-4 border-b bg-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-3">

            <form class="flex flex-wrap items-center gap-2">

                <input
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search..."
                    class="border border-slate-200 rounded-lg px-3 py-2 text-xs w-48 focus:ring-1 focus:ring-indigo-500">

                <select name="status"
                    class="border border-slate-200 rounded-lg px-2 py-2 text-xs focus:ring-1 focus:ring-indigo-500">
                    <option value="">Status</option>
                    <option value="1" {{ request('status')=='1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status')=='0' ? 'selected' : '' }}>Disabled</option>
                </select>

                <button class="bg-slate-900 text-white px-3 py-2 text-xs rounded-lg hover:bg-black">
                    Apply
                </button>

                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('plans.index') }}"
                       class="text-xs px-3 py-2 bg-slate-200 rounded-lg hover:bg-slate-300">
                        Reset
                    </a>
                @endif

            </form>

            <a href="{{ route('plans.create') }}"
               class="bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-indigo-700">
                + Plan
            </a>

        </div>


        {{-- ================= TABLE ================= --}}
        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide sticky top-0">
                    <tr>
                        <th class="px-5 py-3 text-left">Plan</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">Screens</th>
                        <th class="text-left">Storage</th>
                        <th class="text-left">Status</th>
                        <th class="text-right pr-5">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($plans as $plan)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-5 py-3">
                            <div class="font-medium text-slate-800">{{ $plan->name }}</div>
                            <div class="text-[11px] text-slate-500">
                                {{ count($plan->features ?? []) }} features
                            </div>
                        </td>

                        <td class="font-medium text-slate-700">
                            ₹{{ number_format($plan->price) }}
                        </td>

                        <td class="text-slate-600">
                            {{ $plan->screen_limit }}
                        </td>

                        <td class="text-slate-600">
                            {{ $plan->storage_limit }} MB
                        </td>

                        <td>
                            <form method="POST" action="{{ route('plans.toggle', $plan) }}">
                                @csrf
                                <button>

                                    @if($plan->is_active)
                                        <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 font-medium">
                                            Disabled
                                        </span>
                                    @endif

                                </button>
                            </form>
                        </td>

                        <td class="text-right pr-5">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('plans.show',$plan) }}"
                                   class="p-2 rounded hover:bg-blue-50 text-blue-600"
                                   title="View">
                                    <i class="fa fa-eye text-xs"></i>
                                </a>

                                <a href="{{ route('plans.edit',$plan) }}"
                                   class="p-2 rounded hover:bg-indigo-50 text-indigo-600"
                                   title="Edit">
                                    <i class="fa fa-pen text-xs"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('plans.destroy',$plan) }}"
                                      onsubmit="return confirm('Delete plan?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="p-2 rounded hover:bg-red-50 text-red-600"
                                            title="Delete">
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-14 text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa fa-credit-card text-xl"></i>
                                <span>No plans found</span>
                            </div>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================= PAGINATION ================= --}}
        @if($plans->hasPages())
            <div class="px-5 py-3 border-t bg-slate-50">
                {{ $plans->links() }}
            </div>
        @endif

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
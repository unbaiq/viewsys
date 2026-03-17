@extends('layouts.app')

@section('header','Clusters')

@section('content')

<div class="p-6 max-w-8xl mx-auto">

    <!-- Top Bar -->
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-xl font-semibold text-gray-800">Clusters</h1>
            <p class="text-sm text-gray-500">Manage your screen clusters</p>
        </div>

        <a href="{{ route('clusters.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg shadow hover:opacity-90">
            + Create Cluster
        </a>

    </div>


    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif


    <!-- Table -->
    <div class="bg-white border rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-50 text-left text-sm text-gray-600">
                <tr>
                    <th class="p-4">Cluster</th>
                    <th class="p-4">Location</th>
                    <th class="p-4">Layout</th>
                    <th class="p-4">Screens</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($clusters as $cluster)

                <tr class="hover:bg-gray-50">

                    <!-- Name -->
                    <td class="p-4">
                        <div class="font-medium text-gray-800">
                            {{ $cluster->name }}
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $cluster->description }}
                        </div>
                    </td>

                    <!-- Location -->
                    <td class="p-4 text-sm text-gray-600">
                        {{ $cluster->location ?? '-' }}
                    </td>

                    <!-- Layout -->
                    <td class="p-4 text-sm capitalize">
                        {{ str_replace('_',' ',$cluster->type) }}
                    </td>

                    <!-- Screen Count -->
                    <td class="p-4 text-sm">
                        {{ $cluster->screens_count ?? 0 }} Screens
                    </td>

                    <!-- Status -->
                    <td class="p-4">

                        @if($cluster->is_active)
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                Active
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-600">
                                Disabled
                            </span>
                        @endif

                    </td>

                    <!-- Actions -->
                    <td class="p-4 text-right space-x-2">

                        <a href="{{ route('clusters.show',$cluster) }}"
                           class="text-blue-600 text-sm hover:underline">
                           View
                        </a>

                        <a href="{{ route('clusters.edit',$cluster) }}"
                           class="text-indigo-600 text-sm hover:underline">
                           Edit
                        </a>

                        <form action="{{ route('clusters.destroy',$cluster) }}"
                              method="POST"
                              class="inline">

                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Delete this cluster?')"
                                    class="text-red-600 text-sm hover:underline">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-500">
                        No clusters created yet
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- Pagination -->
    <div class="mt-6">
        {{ $clusters->links() }}
    </div>

</div>

@endsection
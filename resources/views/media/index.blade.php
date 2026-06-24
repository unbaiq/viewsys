@extends('layouts.app')

@section('header','Media Library')

@section('content')

<div class="max-w-8xl mx-auto space-y-5">

    @if(session('success'))
        <div id="flash-alert" class="flex items-center p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-check-circle mr-2"></i>
            <div>
                <span class="font-semibold">Success!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-alert" class="flex items-center p-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 transition-opacity duration-500" role="alert">
            <i class="fa fa-exclamation-circle mr-2"></i>
            <div>
                <span class="font-semibold">Alert!</span> {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm">

        <form method="GET" class="flex flex-wrap items-center gap-3">

            {{-- SEARCH --}}
            <input name="search"
                value="{{ request('search') }}"
                placeholder="Search media..."
                class="w-52 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 outline-none">

            {{-- TYPE --}}
            <select name="type"
                class="border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All</option>
                <option value="image" {{ request('type')=='image'?'selected':'' }}>Images</option>
                <option value="video" {{ request('type')=='video'?'selected':'' }}>Videos</option>
            </select>

            {{-- SORT --}}
            <select name="sort"
                class="border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">Sort</option>
                <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>Latest</option>
                <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>Oldest</option>
                <option value="name_asc" {{ request('sort')=='name_asc'?'selected':'' }}>A-Z</option>
                <option value="name_desc" {{ request('sort')=='name_desc'?'selected':'' }}>Z-A</option>
                <option value="size_big" {{ request('sort')=='size_big'?'selected':'' }}>Largest</option>
                <option value="size_small" {{ request('sort')=='size_small'?'selected':'' }}>Smallest</option>
            </select>

            <button class="bg-indigo-600 text-white px-4 py-2 text-xs rounded-xl hover:bg-indigo-700 transition">
                Apply
            </button>

            <a href="{{ route('media.index') }}"
               class="text-xs text-slate-500 hover:text-slate-700 transition">
                Reset
            </a>

            <div class="ml-auto flex items-center gap-3">
                <span class="text-xs text-slate-400">
                    {{ $media->total() }} items
                </span>

                <a href="{{ route('media.create') }}"
                   class="bg-indigo-600 text-white px-4 py-2 text-xs rounded-xl hover:bg-indigo-700 transition">
                    + Upload
                </a>
            </div>

        </form>

    </div>


    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">

    @forelse($media as $item)

        <div class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg transition">

            <div class="h-36 bg-slate-100 relative overflow-hidden">

            @if($item->isImage())
                <img src="{{ asset('storage/'.$item->file_path) }}"
                     class="h-full w-full object-cover group-hover:scale-105 transition duration-500"
                     onerror="this.src='https://via.placeholder.com/300x200'">
            @else
                <div class="video-card h-full w-full">
                    <video class="h-full w-full object-cover"
                           muted loop playsinline preload="metadata">
                        <source src="{{ asset('storage/'.$item->file_path) }}">
                    </video>
                </div>
            @endif

            <div class="absolute top-2 left-2 text-[10px] px-2 py-1 rounded-full bg-black/60 text-white uppercase font-medium tracking-wide">
                {{ $item->type }}
            </div>

            </div>

            <div class="p-3 space-y-1">

                <div class="text-xs font-semibold text-slate-800 truncate" title="{{ $item->name }}">
                    {{ $item->name }}
                </div>

                <div class="text-[10px] text-slate-400">
                    {{ $item->size_formatted }}
                </div>

                @if($item->final_duration)
                    <div class="text-[10px] text-indigo-600 font-medium">
                        ⏱ {{ $item->duration_formatted }}
                    </div>
                @endif

                <div class="flex justify-between items-center mt-2 text-[11px] pt-1.5 border-t border-slate-50">

                    <div class="flex gap-2">
                        <a href="{{ route('media.show', $item->id) }}" class="text-indigo-600 hover:underline">
                            View
                        </a>
                        <a href="{{ route('media.edit', $item->id) }}" class="text-amber-600 hover:underline">
                            Edit
                        </a>
                    </div>

                    <button type="button" 
                            onclick="confirmMediaDelete('{{ route('media.destroy', $item->id) }}', '{{ addslashes($item->name) }}')"
                            class="text-red-500 hover:underline">
                        Delete
                    </button>

                </div>

            </div>

        </div>

    @empty

        <div class="col-span-full">
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center">
                <div class="text-slate-400 text-sm">
                    No media uploaded yet
                </div>
                <a href="{{ route('media.create') }}"
                   class="mt-4 inline-block bg-indigo-600 text-white px-5 py-2 text-xs rounded-xl hover:bg-indigo-700 transition">
                    Upload Media
                </a>
            </div>
        </div>

    @endforelse

    </div>


    @if($media->hasPages())
        <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs">
            {{ $media->links() }}
        </div>
    @endif

</div>


<div id="deleteMediaModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md p-5 transform transition-all space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Delete Media Item</h3>
                <p class="text-xs text-slate-500 mt-0.5">Are you sure you want to permanently delete <span id="deleteMediaName" class="font-medium text-slate-800"></span>? This file will be unlinked from any active playlists.</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="closeMediaDeleteModal()" class="px-3 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-medium transition">
                Cancel
            </button>
            <form id="deleteMediaForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-xs font-medium transition">
                    Delete File
                </button>
            </form>
        </div>
    </div>
</div>


<script>
    // Handle Video Hovers
    document.querySelectorAll('.video-card').forEach(card => {
        const video = card.querySelector('video');

        card.addEventListener('mouseenter', () => {
            video.currentTime = 0;
            video.play().catch(()=>{});
        });

        card.addEventListener('mouseleave', () => {
            video.pause();
            video.currentTime = 0;
        });
    });

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

    // Custom Modal Controls
    function confirmMediaDelete(url, mediaName) {
        document.getElementById('deleteMediaName').innerText = mediaName;
        document.getElementById('deleteMediaForm').setAttribute('action', url);
        document.getElementById('deleteMediaModal').classList.remove('hidden');
    }

    function closeMediaDeleteModal() {
        document.getElementById('deleteMediaModal').classList.add('hidden');
    }
</script>

@endsection
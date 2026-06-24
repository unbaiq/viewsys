@extends('layouts.app')

@section('header',$playlist->name)

@section('content')

<div class="max-w-7xl mx-auto">

<div class="grid md:grid-cols-2 gap-6">

<!-- ================= LEFT: PLAYLIST ================= -->
<div class="bg-white rounded-3xl shadow-sm border flex flex-col">

    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Playlist Media</h3>
    </div>

    <!-- FILTER -->
    <div class="p-4 bg-gray-50 border-b flex flex-wrap gap-2">

        <select id="sortPlaylist" class="border rounded-xl px-3 py-2 text-sm">
            <option value="">Sort</option>
            <option value="name_asc">A → Z</option>
            <option value="name_desc">Z → A</option>
            <option value="date_new">Newest</option>
            <option value="date_old">Oldest</option>
        </select>

        <input type="text" id="searchPlaylist"
            placeholder="Search..."
            class="border rounded-xl px-3 py-2 text-sm w-40">

        <input type="date" id="fromDatePlaylist"
            class="border rounded-xl px-2 py-2 text-sm">

        <input type="date" id="toDatePlaylist"
            class="border rounded-xl px-2 py-2 text-sm">

    </div>

    <!-- LIST -->
    <div id="playlistContainer" class="divide-y max-h-[500px] overflow-y-auto">

    @foreach($playlist->items as $item)
    <div class="playlist-item flex justify-between items-center px-5 py-4 hover:bg-gray-50 transition"
        data-name="{{ strtolower($item->media->name) }}"
        data-date="{{ $item->created_at }}">

        <div class="flex items-center gap-4">

            @if($item->media->type === 'image')
                <img src="{{ asset('storage/'.$item->media->file_path) }}"
                     class="w-14 h-14 rounded-xl object-cover border">
            @else
                <div class="video-card w-14 h-14 rounded-xl overflow-hidden">
                    <video class="w-full h-full object-cover" muted loop>
                        <source src="{{ asset('storage/'.$item->media->file_path) }}">
                    </video>
                </div>
            @endif

            <div>
                <div class="text-sm font-semibold">
                    {{ $item->media->name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ ucfirst($item->media->type) }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('playlist-items.destroy',$item) }}">
            @csrf @method('DELETE')
            <button class="text-red-500 text-xs hover:underline">Remove</button>
        </form>

    </div>
    @endforeach

    </div>

</div>

<!-- ================= RIGHT: MEDIA ================= -->
<div class="bg-white rounded-3xl shadow-sm border flex flex-col">

    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Media Library</h3>
    </div>

    <!-- FILTER -->
    <div class="p-4 bg-gray-50 border-b flex flex-wrap gap-2">

        <select id="sortMedia" class="border rounded-xl px-3 py-2 text-sm">
            <option value="">Sort</option>
            <option value="name_asc">A → Z</option>
            <option value="name_desc">Z → A</option>
            <option value="date_new">Newest</option>
            <option value="date_old">Oldest</option>
        </select>

        <input type="text" id="searchMedia"
            placeholder="Search..."
            class="border rounded-xl px-3 py-2 text-sm w-40">

        <input type="date" id="fromDateMedia"
            class="border rounded-xl px-2 py-2 text-sm">

        <input type="date" id="toDateMedia"
            class="border rounded-xl px-2 py-2 text-sm">

    </div>

    <!-- LIST -->
    <div id="mediaContainer" class="space-y-2 p-3 max-h-[500px] overflow-y-auto">

    @foreach($media as $item)
    <form method="POST"
          action="{{ route('playlists.addMedia',$playlist) }}"
          class="media-item flex justify-between items-center border rounded-xl px-4 py-3 hover:shadow-md transition"
          data-name="{{ strtolower($item->name) }}"
          data-date="{{ $item->created_at }}">

        @csrf

        <div class="flex items-center gap-4">

            @if($item->type === 'image')
                <img src="{{ asset('storage/'.$item->file_path) }}"
                     class="w-12 h-12 rounded object-cover border">
            @else
                <div class="video-card w-12 h-12 overflow-hidden rounded">
                    <video class="w-full h-full object-cover" muted loop>
                        <source src="{{ asset('storage/'.$item->file_path) }}">
                    </video>
                </div>
            @endif

            <div>
                <div class="text-sm font-medium">
                    {{ $item->name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ ucfirst($item->type) }}
                </div>
            </div>

        </div>

        <input type="hidden" name="media_id" value="{{ $item->id }}">

        <button class="text-indigo-600 text-sm font-semibold hover:underline">
            + Add
        </button>

    </form>
    @endforeach

    </div>

</div>

</div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    // VIDEO HOVER
    document.querySelectorAll('.video-card').forEach(card => {
        const video = card.querySelector('video');
        video.muted = true;

        card.addEventListener('mouseenter', () => video.play());
        card.addEventListener('mouseleave', () => {
            video.pause();
            video.currentTime = 0;
        });
    });

    // FILTER FUNCTION
    function setupFilter(sortId, searchId, containerId, itemClass, fromDateId, toDateId) {

        const sort = document.getElementById(sortId);
        const search = document.getElementById(searchId);
        const container = document.getElementById(containerId);
        const fromDate = document.getElementById(fromDateId);
        const toDate = document.getElementById(toDateId);

        const items = Array.from(container.querySelectorAll(itemClass));

        function update() {

            let searchValue = search.value.toLowerCase();
            let from = fromDate.value ? new Date(fromDate.value) : null;
            let to = toDate.value ? new Date(toDate.value) : null;

            let filtered = items.filter(i => {

                let nameMatch = i.dataset.name.includes(searchValue);
                let itemDate = new Date(i.dataset.date);

                let fromMatch = from ? itemDate >= from : true;
                let toMatch = to ? itemDate <= to : true;

                return nameMatch && fromMatch && toMatch;
            });

            switch(sort.value) {
                case 'name_asc':
                    filtered.sort((a,b)=>a.dataset.name.localeCompare(b.dataset.name));
                    break;
                case 'name_desc':
                    filtered.sort((a,b)=>b.dataset.name.localeCompare(a.dataset.name));
                    break;
                case 'date_new':
                    filtered.sort((a,b)=>new Date(b.dataset.date)-new Date(a.dataset.date));
                    break;
                case 'date_old':
                    filtered.sort((a,b)=>new Date(a.dataset.date)-new Date(b.dataset.date));
                    break;
            }

            container.innerHTML = '';
            filtered.forEach(i => container.appendChild(i));
        }

        sort.addEventListener('change', update);
        search.addEventListener('keyup', update);
        fromDate.addEventListener('change', update);
        toDate.addEventListener('change', update);
    }

    // APPLY BOTH
    setupFilter(
        'sortPlaylist',
        'searchPlaylist',
        'playlistContainer',
        '.playlist-item',
        'fromDatePlaylist',
        'toDatePlaylist'
    );

    setupFilter(
        'sortMedia',
        'searchMedia',
        'mediaContainer',
        '.media-item',
        'fromDateMedia',
        'toDateMedia'
    );

});
</script>

@endsection
@extends('layouts.app')

@section('header','Screens')

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

        <div class="px-5 py-4 border-b bg-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-3">

            <form method="GET" class="flex flex-wrap items-center gap-2">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search screen, device, location..."
                    class="border border-slate-200 rounded-lg px-3 py-2 text-xs w-56 focus:ring-1 focus:ring-indigo-500 outline-none">

                <select name="status"
                    class="border border-slate-200 rounded-lg px-2 py-2 text-xs focus:ring-1 focus:ring-indigo-500 outline-none">
                    <option value="">All Status</option>
                    <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>Online</option>
                    <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>Offline</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button class="bg-slate-900 text-white px-3 py-2 text-xs rounded-lg hover:bg-black transition">
                    Apply
                </button>

                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('screens.index') }}"
                       class="text-xs px-3 py-2 bg-slate-200 rounded-lg hover:bg-slate-300 transition">
                        Reset
                    </a>
                @endif

            </form>

            <div class="flex gap-2">

                <a href="{{ route('screens.map') }}"
                   class="bg-green-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    🗺 Map
                </a>

                <a href="{{ route('screens.create') }}"
                   class="bg-indigo-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    + Screen
                </a>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wide sticky top-0">
                    <tr>
                        <th class="px-5 py-3 text-left">Screen</th>
                        <th class="text-left">Company</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Last Seen</th>
                        <th class="text-left">Preview</th>
                        <th class="text-right pr-5">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($screens as $screen)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-5 py-3">
                            <div class="font-medium text-slate-800">{{ $screen->name }}</div>
                            <div class="text-[11px] text-slate-500">{{ $screen->device_id }}</div>
                        </td>

                        <td class="text-slate-600">
                            {{ optional($screen->company)->name ?? '-' }}
                        </td>

                        <td>
                            @if($screen->status === 'inactive')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-medium">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    Inactive
                                </span>
                            @elseif($screen->isOnline())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Online
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-600 font-medium">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                    Offline
                                </span>
                            @endif
                        </td>

                        <td class="text-slate-500" title="{{ $screen->last_seen }}">
                            {{ $screen->last_seen ? $screen->last_seen->diffForHumans() : '-' }}
                        </td>

                        <td>
                            @if($screen->last_screenshot)
                                <img src="{{ asset('storage/'.$screen->last_screenshot) }}"
                                     class="w-28 h-16 object-cover rounded border">
                            @elseif($screen->request_screenshot)
                                <span class="text-yellow-600 text-[11px] font-medium animate-pulse">
                                    Capturing...
                                </span>
                            @else
                                <span class="text-slate-400 text-[11px]">
                                    No preview
                                </span>
                            @endif
                        </td>

                        <td class="text-right pr-5">
                            <div class="flex justify-end gap-2">

                                <button onclick="event.stopPropagation(); openMapOnly(
                                    '{{ $screen->name }}',
                                    '{{ $screen->latitude }}',
                                    '{{ $screen->longitude }}'
                                )"
                                class="p-2 rounded-lg hover:bg-green-50 text-green-600 transition"
                                title="Locate on Map">
                                    <i class="fa-solid fa-location-dot text-sm"></i>
                                </button>

                                <form method="POST" action="{{ route('screens.screenshot',$screen->id) }}">
                                    @csrf
                                    <button type="submit" onclick="event.stopPropagation()" 
                                        class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition"
                                        title="Capture Screenshot">
                                        <i class="fa-solid fa-camera text-sm"></i>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('screens.restart',$screen->id) }}">
                                    @csrf
                                    <button type="submit" onclick="event.stopPropagation()"
                                        class="p-2 rounded-lg hover:bg-yellow-50 text-yellow-600 transition"
                                        title="Restart Screen">
                                        <i class="fa-solid fa-rotate-right text-sm"></i>
                                    </button>
                                </form>

                                <a href="{{ route('screens.show',$screen->id) }}"
                                   onclick="event.stopPropagation()"
                                   class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition"
                                   title="View">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>

                                <a href="{{ route('screens.edit',$screen->id) }}"
                                   onclick="event.stopPropagation()"
                                   class="p-2 rounded-lg hover:bg-indigo-50 text-indigo-600 transition"
                                   title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>

                                <button type="button" 
                                        onclick="event.stopPropagation(); confirmScreenDelete('{{ route('screens.destroy', $screen->id) }}', '{{ addslashes($screen->name) }}')"
                                        class="p-2 rounded-lg hover:bg-red-50 text-red-600 transition"
                                        title="Delete">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-14 text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa fa-tv text-xl"></i>
                                <span>No screens found</span>
                            </div>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="px-5 py-3 border-t bg-slate-50">
            {{ $screens->withQueryString()->links() }}
        </div>

        <div id="mapModal"
             onclick="if(event.target.id==='mapModal') closeMapModal()"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center z-50">

            <div class="bg-white w-[700px] max-w-[95%] rounded-2xl shadow-2xl overflow-hidden transform transition-all">

                <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                    <div>
                        <h2 id="mapTitle" class="text-base font-semibold text-gray-800">Screen Location</h2>
                        <p class="text-xs text-gray-500">View device position on map</p>
                    </div>
                    <button onclick="closeMapModal()" 
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 text-gray-500 transition"
                        title="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-5">
                    <div id="singleMap" class="w-full h-[350px] rounded-xl border border-gray-200 shadow-inner"></div>
                </div>

                <div class="flex justify-end gap-2 px-6 py-3 border-t bg-gray-50">
                    <button onclick="closeMapModal()"
                        class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                        Close
                    </button>
                    <button class="px-4 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white transition">
                        Open in Maps
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<div id="deleteScreenModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md p-5 transform transition-all space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Delete Screen Hardware</h3>
                <p class="text-xs text-slate-500 mt-0.5">Are you sure you want to permanently delete <span id="deleteScreenName" class="font-medium text-slate-800"></span>? This will unregister the player terminal node.</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="closeScreenDeleteModal()" class="px-3 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-medium transition">
                Cancel
            </button>
            <form id="deleteScreenForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg text-xs font-medium transition">
                    Delete Screen
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9Ylg0W05zn6a83cEOQjZETPH8uJDQK0Y"></script>

<script>
let singleMap, singleMarker;

function openMapOnly(name, lat, lng)
{
    document.getElementById('mapModal').classList.remove('hidden');
    document.getElementById('mapTitle').innerText = name;

    if(!lat || !lng){
        alert("Location not available");
        return;
    }

    let position = {
        lat: parseFloat(lat),
        lng: parseFloat(lng)
    };

    if(!singleMap){
        singleMap = new google.maps.Map(document.getElementById("singleMap"), {
            zoom: 15,
            center: position
        });

        singleMarker = new google.maps.Marker({
            position: position,
            map: singleMap
        });

    } else {
        singleMap.setCenter(position);
        singleMarker.setPosition(position);
    }
}

function closeMapModal(){
    document.getElementById('mapModal').classList.add('hidden');
}

// Custom Modal Controls
function confirmScreenDelete(url, name) {
    document.getElementById('deleteScreenName').innerText = name;
    document.getElementById('deleteScreenForm').setAttribute('action', url);
    document.getElementById('deleteScreenModal').classList.remove('hidden');
}

function closeScreenDeleteModal() {
    document.getElementById('deleteScreenModal').classList.add('hidden');
}

// Auto-fade handler for the alert popup
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
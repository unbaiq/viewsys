@extends('layouts.app')

@section('header','Create Schedule')

@section('content')

<style>
/* 🔥 PROFESSIONAL INPUT STYLE */
.input {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 13px;
    background: #fff;
    transition: all 0.2s ease;
}

.input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
}

.input:hover {
    border-color: #c7d2fe;
}

.input:disabled {
    background: #f1f5f9;
    cursor: not-allowed;
}

.input-error {
    border-color: #ef4444;
}

.label {
    font-size: 11px;
    font-weight: 500;
    color: #64748b;
}

.btn-primary {
    background: #6366f1;
    color: white;
    font-size: 12px;
    padding: 8px 16px;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #4f46e5;
}

.btn-secondary {
    background: #e2e8f0;
    font-size: 12px;
    padding: 8px 16px;
    border-radius: 8px;
}
</style>

<div class="max-w-6xl mx-auto">
<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Create Schedule</h2>
    <p class="text-xs text-slate-500">Assign playlist to screen / cluster</p>
</div>

<form method="POST" action="{{ route('schedules.store') }}" class="p-5 space-y-5">
@csrf

<!-- TARGET TYPE -->
<div class="grid grid-cols-3 gap-3 text-xs">

    <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer">
        <input type="radio" name="target_type" value="screen" checked>
        Screen
    </label>

    <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer">
        <input type="radio" name="target_type" value="cluster">
        Cluster
    </label>

    <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer">
        <input type="radio" name="target_type" value="default">
        All Screens
    </label>

</div>

<!-- SCREEN -->
<div id="screenBox" class="space-y-1">
    <label class="label">Screen</label>
    <select name="screen_id" class="input">
        <option value="">Select Screen</option>
        @foreach($screens ?? [] as $screen)
            <option value="{{ $screen->id }}">{{ $screen->name }}</option>
        @endforeach
    </select>
</div>

<!-- CLUSTER -->
<div id="clusterBox" class="space-y-1 hidden">
    <label class="label">Cluster</label>
    <select name="cluster_id" class="input">
        <option value="">Select Cluster</option>
        @foreach($clusters ?? [] as $cluster)
            <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
        @endforeach
    </select>
</div>

<input type="hidden" name="is_default" id="isDefault" value="0">

<!-- PLAYLIST -->
<div class="space-y-1">
    <label class="label">Playlist</label>
    <select name="playlist_id" class="input">
        @foreach($playlists as $playlist)
            <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
        @endforeach
    </select>
</div>

<!-- DATE -->
<div class="grid grid-cols-2 gap-3">
    <div class="space-y-1">
        <label class="label">Start Date</label>
        <input type="date" name="start_date" class="input">
    </div>

    <div class="space-y-1">
        <label class="label">End Date</label>
        <input type="date" name="end_date" class="input">
    </div>
</div>

<!-- TIME -->
<div class="grid grid-cols-2 gap-3">
    <div class="space-y-1">
        <label class="label">Start Time</label>
        <input type="time" name="start_time" class="input">
    </div>

    <div class="space-y-1">
        <label class="label">End Time</label>
        <input type="time" name="end_time" class="input">
    </div>
</div>

<!-- DAYS -->
<div class="space-y-1">
    <label class="label">Days</label>
    <div class="flex flex-wrap gap-2 text-xs">
        @foreach(['mon','tue','wed','thu','fri','sat','sun'] as $day)
            <label class="border px-2 py-1 rounded cursor-pointer">
                <input type="checkbox" name="days_of_week[]" value="{{ $day }}">
                {{ ucfirst($day) }}
            </label>
        @endforeach
    </div>
</div>

<!-- PRIORITY -->
<div class="space-y-1">
    <label class="label">Priority</label>
    <input type="number" name="priority" value="1" class="input">
</div>

<!-- ACTION -->
<div class="flex justify-end gap-2 pt-4 border-t">
    <a href="{{ route('schedules.index') }}" class="btn-secondary">Cancel</a>
    <button class="btn-primary">Create</button>
</div>

</form>
</div>
</div>

<!-- SCRIPT -->
<script>

document.querySelectorAll('[name="target_type"]').forEach(radio => {
    radio.addEventListener('change', function () {

        let type = this.value;

        document.getElementById('screenBox').classList.add('hidden');
        document.getElementById('clusterBox').classList.add('hidden');

        document.getElementById('isDefault').value = 0;

        if (type === 'screen') {
            document.getElementById('screenBox').classList.remove('hidden');
        }

        if (type === 'cluster') {
            document.getElementById('clusterBox').classList.remove('hidden');
        }

        if (type === 'default') {
            document.getElementById('isDefault').value = 1;
        }
    });
});

</script>

@endsection
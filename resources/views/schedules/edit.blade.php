@extends('layouts.app')

@section('header','Edit Schedule')

@section('content')

<style>
/* 🔥 PROFESSIONAL INPUT STYLE RE-INTEGRATED */
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

<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Edit Schedule</h2>
    <p class="text-xs text-slate-500">Update schedule settings</p>
</div>

<form method="POST" action="{{ route('schedules.update',$schedule) }}" class="p-5 space-y-5">
@csrf
@method('PUT')

@php
    $isDefault = $schedule->is_default;
    $isCluster = !$schedule->is_default && $schedule->cluster_id;
    $isScreen  = !$schedule->is_default && !$schedule->cluster_id;
@endphp

<div class="space-y-1">
    <label class="label">Target Type</label>
    <div class="grid grid-cols-3 gap-3 text-xs">
        <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
            <input type="radio" name="target_type" value="screen" {{ $isScreen ? 'checked' : '' }}>
            Screen
        </label>

        <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
            <input type="radio" name="target_type" value="cluster" {{ $isCluster ? 'checked' : '' }}>
            Cluster
        </label>

        <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
            <input type="radio" name="target_type" value="default" {{ $isDefault ? 'checked' : '' }}>
            All Screens
        </label>
    </div>
</div>

<div id="screenBox" class="space-y-1 {{ $isScreen ? '' : 'hidden' }}">
    <label class="label">Screen</label>
    <select name="screen_id" class="input @error('screen_id') input-error @enderror">
        <option value="">Select Screen</option>
        @foreach($screens as $screen)
            <option value="{{ $screen->id }}"
                {{ old('screen_id', $schedule->screen_id) == $screen->id ? 'selected' : '' }}>
                {{ $screen->name }}
            </option>
        @endforeach
    </select>
    @error('screen_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
</div>

<div id="clusterBox" class="space-y-1 {{ $isCluster ? '' : 'hidden' }}">
    <label class="label">Cluster</label>
    <select name="cluster_id" class="input @error('cluster_id') input-error @enderror">
        <option value="">Select Cluster</option>
        @foreach($clusters as $cluster)
            <option value="{{ $cluster->id }}"
                {{ old('cluster_id', $schedule->cluster_id) == $cluster->id ? 'selected' : '' }}>
                {{ $cluster->name }}
            </option>
        @endforeach
    </select>
    @error('cluster_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
</div>

<input type="hidden" name="is_default" id="isDefault" value="{{ $schedule->is_default ? 1 : 0 }}">

<div class="space-y-1">
    <label class="label">Playlist</label>
    <select name="playlist_id" class="input @error('playlist_id') input-error @enderror">
        @foreach($playlists as $playlist)
            <option value="{{ $playlist->id }}"
                {{ old('playlist_id', $schedule->playlist_id) == $playlist->id ? 'selected' : '' }}>
                {{ $playlist->name }}
            </option>
        @endforeach
    </select>
    @error('playlist_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
</div>

<div class="grid grid-cols-2 gap-3">
    <div class="space-y-1">
        <label class="label">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $schedule->start_date ? \Carbon\Carbon::parse($schedule->start_date)->format('Y-m-2d') : '') }}" class="input">
    </div>
    <div class="space-y-1">
        <label class="label">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', $schedule->end_date ? \Carbon\Carbon::parse($schedule->end_date)->format('Y-m-2d') : '') }}" class="input">
    </div>
</div>

<div class="grid grid-cols-2 gap-3">
    <div class="space-y-1">
        <label class="label">Start Time</label>
        <input type="time" name="start_time" value="{{ old('start_time', $schedule->start_time) }}" class="input">
    </div>
    <div class="space-y-1">
        <label class="label">End Time</label>
        <input type="time" name="end_time" value="{{ old('end_time', $schedule->end_time) }}" class="input">
    </div>
</div>

<div class="space-y-1">
    <label class="label">Active Days</label>
    <div class="flex flex-wrap gap-2 text-xs">
        @foreach(['mon','tue','wed','thu','fri','sat','sun'] as $day)
            <label class="border px-3 py-1.5 rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition flex items-center gap-1.5">
                <input type="checkbox" name="days_of_week[]" value="{{ $day }}"
                    {{ in_array($day, old('days_of_week', $schedule->days_of_week ?? [])) ? 'checked' : '' }}>
                {{ ucfirst($day) }}
            </label>
        @endforeach
    </div>
</div>

<div class="space-y-1">
    <label class="label">Priority</label>
    <input type="number" name="priority"
        value="{{ old('priority', $schedule->priority) }}"
        class="input @error('priority') input-error @enderror">
    @error('priority') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
</div>

<div class="flex justify-end gap-2 pt-4 border-t">
    <a href="{{ route('schedules.index') }}" class="btn-secondary">Cancel</a>
    <button class="btn-primary">Update</button>
</div>

</form>
</div>
</div>

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
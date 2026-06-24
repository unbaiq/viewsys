@extends('layouts.app')

@section('header','Platform Settings')

@section('content')

<div class="max-w-8xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- ================= HEADER ================= -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">System Settings</h2>
    <p class="text-xs text-slate-500">Configure platform behavior</p>
</div>

<form method="POST" action="{{ route('settings.update') }}" class="p-5 space-y-5">
@csrf

<!-- ================= GRID ================= -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- PLATFORM NAME -->
<div>
    <label class="text-xs font-medium text-slate-600">Platform Name</label>
    <input
        name="platform_name"
        value="{{ $settings['platform_name'] ?? '' }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- SUPPORT EMAIL -->
<div>
    <label class="text-xs font-medium text-slate-600">Support Email</label>
    <input
        name="support_email"
        value="{{ $settings['support_email'] ?? '' }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- PLAYER SYNC -->
<div>
    <label class="text-xs font-medium text-slate-600">Player Sync (sec)</label>
    <input
        name="player_sync_interval"
        value="{{ $settings['player_sync_interval'] ?? 30 }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- ORIENTATION -->
<div>
    <label class="text-xs font-medium text-slate-600">Default Orientation</label>

    <select
        name="default_orientation"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">

        <option value="landscape"
            {{ ($settings['default_orientation'] ?? '')=='landscape' ? 'selected':'' }}>
            Landscape
        </option>

        <option value="portrait"
            {{ ($settings['default_orientation'] ?? '')=='portrait' ? 'selected':'' }}>
            Portrait
        </option>

    </select>
</div>

<!-- CDN -->
<div>
    <label class="text-xs font-medium text-slate-600">CDN URL</label>
    <input
        name="cdn_url"
        value="{{ $settings['cdn_url'] ?? '' }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- MAX UPLOAD -->
<div>
    <label class="text-xs font-medium text-slate-600">Max Upload (MB)</label>
    <input
        name="max_upload"
        value="{{ $settings['max_upload'] ?? 500 }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

</div>


<!-- ================= ACTIONS ================= -->
<div class="flex justify-end pt-4 border-t">

    <button
        class="px-5 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Save
    </button>

</div>

</form>

</div>

</div>

@endsection
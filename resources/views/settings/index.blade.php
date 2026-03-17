@extends('layouts.app')

@section('header','Platform Settings')

@section('content')

<div class="max-w-5xl mx-auto">

<div class="bg-white rounded-xl shadow-sm border p-8">

<h2 class="text-xl font-semibold mb-6">
System Settings
</h2>

<form method="POST" action="{{ route('settings.update') }}">
@csrf

<div class="grid grid-cols-2 gap-6">

<!-- PLATFORM NAME -->
<div>
<label class="text-sm text-gray-600">Platform Name</label>
<input
name="platform_name"
value="{{ $settings['platform_name'] ?? '' }}"
class="w-full border rounded-lg px-4 py-2 mt-1">
</div>

<!-- SUPPORT EMAIL -->
<div>
<label class="text-sm text-gray-600">Support Email</label>
<input
name="support_email"
value="{{ $settings['support_email'] ?? '' }}"
class="w-full border rounded-lg px-4 py-2 mt-1">
</div>

<!-- PLAYER SYNC -->
<div>
<label class="text-sm text-gray-600">Player Sync Interval (seconds)</label>
<input
name="player_sync_interval"
value="{{ $settings['player_sync_interval'] ?? 30 }}"
class="w-full border rounded-lg px-4 py-2 mt-1">
</div>

<!-- DEFAULT ORIENTATION -->
<div>
<label class="text-sm text-gray-600">Default Screen Orientation</label>

<select
name="default_orientation"
class="w-full border rounded-lg px-4 py-2 mt-1">

<option value="landscape"
@if(($settings['default_orientation'] ?? '')=='landscape') selected
@endif>Landscape</option>

<option value="portrait"
@if(($settings['default_orientation'] ?? '')=='portrait') selected
@endif>Portrait</option>

</select>
</div>

<!-- CDN URL -->
<div>
<label class="text-sm text-gray-600">CDN URL</label>
<input
name="cdn_url"
value="{{ $settings['cdn_url'] ?? '' }}"
class="w-full border rounded-lg px-4 py-2 mt-1">
</div>

<!-- MAX UPLOAD -->
<div>
<label class="text-sm text-gray-600">Max Upload Size (MB)</label>
<input
name="max_upload"
value="{{ $settings['max_upload'] ?? 500 }}"
class="w-full border rounded-lg px-4 py-2 mt-1">
</div>

</div>

<div class="mt-8 flex justify-end">

<button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
Save Settings
</button>

</div>

</form>

</div>

</div>

@endsection
@extends('layouts.app')

@section('header','Analytics')

@section('content')

<div class="space-y-8">

<!-- Stats -->
<div class="grid grid-cols-4 gap-6">

<div class="bg-white p-6 rounded-xl shadow">
<div class="text-sm text-gray-500">Total Screens</div>
<div class="text-2xl font-semibold">{{ $totalScreens }}</div>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<div class="text-sm text-gray-500">Online Screens</div>
<div class="text-2xl font-semibold text-green-600">{{ $onlineScreens }}</div>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<div class="text-sm text-gray-500">Offline Screens</div>
<div class="text-2xl font-semibold text-red-600">{{ $offlineScreens }}</div>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<div class="text-sm text-gray-500">Media Files</div>
<div class="text-2xl font-semibold">{{ $totalMedia }}</div>
</div>

</div>


<!-- Second Row -->

<div class="grid grid-cols-3 gap-6">

<div class="bg-white p-6 rounded-xl shadow">
<div class="text-sm text-gray-500">Playlists</div>
<div class="text-2xl font-semibold">{{ $totalPlaylists }}</div>
</div>

@if($companies)

<div class="bg-white p-6 rounded-xl shadow">
<div class="text-sm text-gray-500">Companies</div>
<div class="text-2xl font-semibold">{{ $companies }}</div>
</div>

@endif

<div class="bg-white p-6 rounded-xl shadow">
<div class="text-sm text-gray-500">Storage Used</div>
<div class="text-2xl font-semibold">{{ $storageGB }} GB</div>
</div>

</div>


<!-- Device Activity -->

<div class="bg-white rounded-xl shadow p-6">

<h3 class="font-semibold mb-4">Recent Device Activity</h3>

@foreach($recentLogs as $log)

<div class="flex justify-between border-b py-2 text-sm">

<div>
Screen #{{ $log->screen_id }}
</div>

<div>
{{ $log->status }}
</div>

<div class="text-gray-500">
{{ $log->last_ping }}
</div>

</div>

@endforeach

</div>

</div>

@endsection
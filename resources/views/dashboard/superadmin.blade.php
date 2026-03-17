@extends('layouts.app')

@section('header','Platform Dashboard')

@section('content')

<div class="space-y-8">

<!-- PAGE HEADER -->
<div class="flex items-center justify-between">

<div>
<h1 class="text-2xl font-bold text-gray-800">
Welcome back 👋
</h1>
<p class="text-sm text-gray-500">
Here’s what’s happening with your platform today.
</p>
</div>

<div class="flex gap-3">

<a href="/companies"
class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
Add Company
</a>

<a href="/screens/create"
class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-50">
Add Screen
</a>

</div>

</div>

<!-- STATS -->
<div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">

<div class="bg-white rounded-xl p-6 shadow-sm border">
<div class="flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">Companies</p>
<h3 class="text-2xl font-bold mt-1">{{ $stats['companies'] }}</h3>
</div>

<div class="bg-indigo-50 p-3 rounded-lg">
<i data-lucide="building-2" class="w-5 h-5 text-indigo-600"></i>
</div>
</div>
</div>


<div class="bg-white rounded-xl p-6 shadow-sm border">
<div class="flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">Users</p>
<h3 class="text-2xl font-bold mt-1">{{ $stats['users'] }}</h3>
</div>

<div class="bg-blue-50 p-3 rounded-lg">
<i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
</div>
</div>
</div>


<div class="bg-white rounded-xl p-6 shadow-sm border">
<div class="flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">Total Screens</p>
<h3 class="text-2xl font-bold mt-1">{{ $stats['screens'] }}</h3>
</div>

<div class="bg-purple-50 p-3 rounded-lg">
<i data-lucide="monitor" class="w-5 h-5 text-purple-600"></i>
</div>
</div>
</div>


<div class="bg-white rounded-xl p-6 shadow-sm border">
<div class="flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">Media Files</p>
<h3 class="text-2xl font-bold mt-1">{{ $stats['media'] }}</h3>
</div>

<div class="bg-green-50 p-3 rounded-lg">
<i data-lucide="image" class="w-5 h-5 text-green-600"></i>
</div>
</div>
</div>

</div>


<!-- SCREEN STATUS -->
<div class="grid lg:grid-cols-2 gap-6">

<div class="bg-white border rounded-xl p-6 shadow-sm">

<h3 class="font-semibold mb-4">
Screen Status
</h3>

<div class="flex justify-between">

<div class="text-center">
<p class="text-sm text-gray-500">Online</p>
<p class="text-3xl font-bold text-green-600">
{{ $stats['online_screens'] }}
</p>
</div>

<div class="text-center">
<p class="text-sm text-gray-500">Offline</p>
<p class="text-3xl font-bold text-red-600">
{{ $stats['offline_screens'] }}
</p>
</div>

</div>

</div>


<div class="bg-white border rounded-xl p-6 shadow-sm">

<h3 class="font-semibold mb-4">
System Health
</h3>

<div class="space-y-3 text-sm">

<div class="flex justify-between">
<span>API Status</span>
<span class="text-green-600 font-medium">Healthy</span>
</div>

<div class="flex justify-between">
<span>Storage</span>
<span class="text-yellow-600 font-medium">Normal</span>
</div>

<div class="flex justify-between">
<span>Queue Workers</span>
<span class="text-green-600 font-medium">Running</span>
</div>

</div>

</div>

</div>


<!-- RECENT ACTIVITY -->
<div class="bg-white border rounded-xl shadow-sm">

<div class="p-6 border-b flex justify-between items-center">

<h3 class="font-semibold">
Recent Activity
</h3>

<a href="/logs"
class="text-sm text-indigo-600 hover:underline">
View All
</a>

</div>

<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600">
<tr>
<th class="p-3 text-left">User</th>
<th class="p-3 text-left">Action</th>
<th class="p-3 text-left">Type</th>
<th class="p-3 text-left">Time</th>
</tr>
</thead>

<tbody>

@foreach($recentLogs as $log)

<tr class="border-t hover:bg-gray-50">

<td class="p-3">
{{ $log->user->name ?? 'System' }}
</td>

<td class="p-3">
{{ $log->action }}
</td>

<td class="p-3">
<span class="px-2 py-1 text-xs bg-gray-100 rounded">
{{ $log->type }}
</span>
</td>

<td class="p-3 text-gray-500">
{{ $log->created_at->diffForHumans() }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection
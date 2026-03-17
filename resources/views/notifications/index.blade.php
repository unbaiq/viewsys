@extends('layouts.app')

@section('header','Notifications')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

<!-- Header -->
<div class="flex items-center justify-between">

<h2 class="text-xl font-semibold text-gray-800">
System Notifications
</h2>

<a href="{{ route('notifications.index') }}"
class="text-sm text-indigo-600 hover:underline">
Refresh
</a>

</div>


<!-- Notification Card -->
<div class="bg-white rounded-xl shadow border overflow-hidden">

@forelse($notifications as $note)

<div class="flex items-start justify-between p-5 border-b hover:bg-gray-50 transition">

<!-- Left -->
<div class="flex gap-4">

<!-- Icon -->
<div class="w-10 h-10 flex items-center justify-center rounded-lg
@if($note->type === 'device') bg-red-100 text-red-600
@elseif($note->type === 'media') bg-indigo-100 text-indigo-600
@elseif($note->type === 'user') bg-blue-100 text-blue-600
@elseif($note->type === 'storage') bg-yellow-100 text-yellow-600
@else bg-gray-100 text-gray-600
@endif
">

<i data-lucide="bell" class="w-5 h-5"></i>

</div>

<!-- Text -->
<div>

<div class="flex items-center gap-2">

<span class="font-medium text-gray-800">
{{ $note->title }}
</span>

@if(!$note->read)
<span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-600 rounded">
New
</span>
@endif

</div>

<p class="text-sm text-gray-500 mt-1">
{{ $note->message }}
</p>

<p class="text-xs text-gray-400 mt-2">
{{ $note->created_at->diffForHumans() }}
</p>

</div>

</div>

<!-- Action -->
@if(!$note->read)

<form method="POST" action="{{ route('notifications.read',$note->id) }}">
@csrf

<button
class="text-sm text-indigo-600 hover:text-indigo-800">
Mark Read
</button>

</form>

@endif

</div>

@empty

<div class="p-12 text-center text-gray-400">
No notifications yet
</div>

@endforelse

</div>


<!-- Pagination -->
<div>
{{ $notifications->links() }}
</div>

</div>

@endsection
@extends('layouts.app')

@section('header','Notifications')

@section('content')

<div class="max-w-8xl mx-auto space-y-4">

<!-- ================= HEADER ================= -->
<div class="flex items-center justify-between">

    <div>
        <h2 class="text-base font-semibold text-slate-900">System Notifications</h2>
        <p class="text-xs text-slate-500">Recent activity & alerts</p>
    </div>

    <a href="{{ route('notifications.index') }}"
       class="text-xs text-indigo-600 hover:text-indigo-800">
        Refresh
    </a>

</div>


<!-- ================= LIST ================= -->
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden">

@forelse($notifications as $note)

<div class="flex items-start justify-between px-5 py-4 border-b hover:bg-slate-50 transition">

<!-- LEFT -->
<div class="flex gap-3">

<!-- ICON -->
<div class="w-8 h-8 flex items-center justify-center rounded-lg text-xs
@if($note->type === 'device') bg-red-100 text-red-600
@elseif($note->type === 'media') bg-indigo-100 text-indigo-600
@elseif($note->type === 'user') bg-blue-100 text-blue-600
@elseif($note->type === 'storage') bg-yellow-100 text-yellow-600
@else bg-slate-100 text-slate-600
@endif
">

<i data-lucide="bell" class="w-4 h-4"></i>

</div>

<!-- TEXT -->
<div>

<div class="flex items-center gap-2">

<span class="text-sm font-medium text-slate-800">
{{ $note->title }}
</span>

@if(!$note->read)
<span class="px-2 py-0.5 text-[10px] bg-blue-100 text-blue-600 rounded">
New
</span>
@endif

</div>

<p class="text-xs text-slate-500 mt-0.5">
{{ $note->message }}
</p>

<p class="text-[10px] text-slate-400 mt-1">
{{ $note->created_at->diffForHumans() }}
</p>

</div>

</div>


<!-- ACTION -->
@if(!$note->read)

<form method="POST" action="{{ route('notifications.read',$note->id) }}">
@csrf

<button
class="text-xs text-indigo-600 hover:text-indigo-800">
Read
</button>

</form>

@endif

</div>

@empty

<div class="py-12 text-center text-slate-400 text-sm">
No notifications
</div>

@endforelse

</div>


<!-- ================= PAGINATION ================= -->
@if($notifications->hasPages())
<div class="bg-white border border-slate-200 rounded-xl px-4 py-3">
    {{ $notifications->links() }}
</div>
@endif

</div>

@endsection
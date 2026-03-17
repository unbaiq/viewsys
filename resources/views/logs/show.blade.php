@extends('layouts.app')

@section('header','Log Details')

@section('content')

<div class="bg-white p-6 rounded-xl shadow max-w-xl">

<h2 class="text-xl font-semibold mb-4">
{{ $log->action }}
</h2>

<p><b>User:</b> {{ $log->user->name ?? 'System' }}</p>

<p><b>Type:</b> {{ $log->type }}</p>

<p><b>IP:</b> {{ $log->ip }}</p>

<p><b>Date:</b> {{ $log->created_at }}</p>

@if($log->description)
<p class="mt-4"><b>Description</b></p>
<p class="text-gray-600">{{ $log->description }}</p>
@endif

@if($log->meta)
<p class="mt-4"><b>Meta</b></p>

<pre class="bg-gray-100 p-3 rounded text-xs">
{{ json_encode($log->meta, JSON_PRETTY_PRINT) }}
</pre>
@endif

</div>

@endsection
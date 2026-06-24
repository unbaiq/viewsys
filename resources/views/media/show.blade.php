@extends('layouts.app')

@section('header','Media Details')

@section('content')

<div class="max-w-4xl mx-auto space-y-5">

<!-- ================= PREVIEW ================= -->
<div class="bg-white border border-slate-200 rounded-xl p-4">

    @if($media->type === 'image')

        <img
            src="{{ asset('storage/'.$media->file_path) }}"
            class="w-full max-h-[400px] object-contain rounded-lg">

    @else

        <video controls class="w-full max-h-[400px] rounded-lg">
            <source src="{{ asset('storage/'.$media->file_path) }}">
        </video>

    @endif

</div>


<!-- ================= INFO ================= -->
<div class="bg-white border border-slate-200 rounded-xl p-5">

    <div class="text-base font-semibold text-slate-900">
        {{ $media->name }}
    </div>

    <div class="mt-2 text-xs text-slate-500 space-y-1">

        <div>
            Size: {{ number_format($media->size/1024/1024,2) }} MB
        </div>

        <div>
            Type: {{ strtoupper($media->type) }}
        </div>

        <div>
            Uploaded: {{ $media->created_at->diffForHumans() }}
        </div>

    </div>

</div>


<!-- ================= ACTIONS ================= -->
<div class="flex items-center justify-between">

    <a href="{{ route('media.index') }}"
       class="text-xs text-slate-500 hover:text-slate-700">
        ← Back
    </a>

    <div class="flex gap-2">

        <a href="{{ asset('storage/'.$media->file_path) }}"
           download
           class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Download
        </a>

        <form method="POST"
              action="{{ route('media.destroy',$media) }}"
              onsubmit="return confirm('Delete this media?')">
            @csrf
            @method('DELETE')

            <button
                class="px-4 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700">
                Delete
            </button>

        </form>

    </div>

</div>

</div>

@endsection
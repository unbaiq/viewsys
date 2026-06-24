@extends('layouts.app')

@section('header','Edit Media')

@section('content')

<div class="h-full overflow-y-auto px-3 sm:px-4 md:px-6 py-4">
<div class="max-w-4xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-sm font-semibold text-slate-900">Edit Media</h2>
    <p class="text-xs text-slate-500">Update media details</p>
</div>

<form method="POST" action="{{ route('media.update', $media) }}" enctype="multipart/form-data" class="p-4 space-y-5">
@csrf
@method('PUT')

{{-- SUCCESS --}}
@if(session('success'))
<div class="p-2 bg-green-50 border border-green-200 text-green-700 text-xs rounded">
    {{ session('success') }}
</div>
@endif

{{-- ERRORS --}}
@if ($errors->any())
<div class="p-2 bg-red-50 border border-red-200 text-red-600 text-xs rounded">
    @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
</div>
@endif

{{-- COMPANY --}}
@if(auth()->user()->hasRole('superadmin'))
<div>
    <label class="text-xs font-medium">Company</label>
    <select name="company_id" class="mt-1 w-full border rounded px-2 py-1.5 text-xs">
        @foreach($companies as $id => $name)
            <option value="{{ $id }}" {{ $media->company_id == $id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>
@endif

{{-- NAME --}}
<div>
    <label class="text-xs font-medium">Media Name</label>
    <input type="text" name="name" id="mediaName"
        value="{{ old('name', $media->name) }}"
        class="mt-1 w-full border rounded px-2 py-1.5 text-xs">
</div>

{{-- FILE REPLACE --}}
<div>
    <label class="text-xs font-medium">Replace File (optional)</label>

    <div id="dropArea"
        class="mt-1 border-2 border-dashed border-slate-300 rounded-lg p-6 text-center cursor-pointer bg-slate-50 hover:bg-slate-100 transition">

        <p class="text-xs text-slate-500">Click or drag new file (optional)</p>
        <input type="file" name="file" id="fileInput" class="hidden">
    </div>
</div>

{{-- CURRENT PREVIEW --}}
<div>
    <label class="text-xs font-medium">Current Preview</label>

    <div class="mt-2 border rounded p-3 bg-slate-50 text-center">

        @if($media->isImage())
            <img src="{{ asset('storage/'.$media->file_path) }}"
                 class="max-h-48 mx-auto rounded">
        @else
            <video controls class="max-h-48 mx-auto rounded">
                <source src="{{ asset('storage/'.$media->file_path) }}">
            </video>
        @endif

    </div>
</div>

{{-- NEW PREVIEW --}}
<div id="previewWrapper" class="hidden">
    <label class="text-xs font-medium">New Preview</label>

    <div class="mt-2 border rounded p-3 bg-slate-50">
        <img id="imagePreview" class="hidden max-h-48 mx-auto rounded">
        <video id="videoPreview" controls class="hidden max-h-48 mx-auto rounded"></video>
    </div>
</div>

{{-- DISPLAY DURATION --}}
<div id="durationWrapper" class="{{ $media->isImage() ? '' : 'hidden' }}">
    <label class="text-xs font-medium">Display Duration (seconds)</label>

    <input type="number"
           name="display_duration"
           id="displayDuration"
           value="{{ old('display_duration', $media->display_duration ?? 10) }}"
           min="1"
           class="mt-1 w-full border rounded px-2 py-1.5 text-xs">
</div>

<!-- ACTIONS -->
<div class="flex justify-end gap-2 pt-3 border-t">
    <a href="{{ route('media.index') }}" class="px-3 py-1.5 text-xs border rounded">
        Cancel
    </a>

    <button class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded">
        Update
    </button>
</div>

</form>
</div>
</div>
</div>

{{-- SCRIPT --}}
<script>
const dropArea = document.getElementById('dropArea');
const fileInput = document.getElementById('fileInput');
const nameField = document.getElementById('mediaName');

const previewWrapper = document.getElementById('previewWrapper');
const imagePreview = document.getElementById('imagePreview');
const videoPreview = document.getElementById('videoPreview');

const durationWrapper = document.getElementById('durationWrapper');

dropArea.addEventListener('click', () => fileInput.click());

dropArea.addEventListener('dragover', e => {
    e.preventDefault();
    dropArea.classList.add('bg-indigo-50');
});

dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('bg-indigo-50');
});

dropArea.addEventListener('drop', e => {
    e.preventDefault();
    fileInput.files = e.dataTransfer.files;
    handleFile(e.dataTransfer.files[0]);
});

fileInput.addEventListener('change', e => {
    handleFile(e.target.files[0]);
});

function handleFile(file) {
    if(!file) return;

    previewWrapper.classList.remove('hidden');

    if(!nameField.value){
        nameField.value = file.name.split('.').slice(0, -1).join('.');
    }

    if(file.type.startsWith('image')){
        imagePreview.src = URL.createObjectURL(file);
        imagePreview.classList.remove('hidden');
        videoPreview.classList.add('hidden');

        durationWrapper.classList.remove('hidden');
    } else {
        videoPreview.src = URL.createObjectURL(file);
        videoPreview.classList.remove('hidden');
        imagePreview.classList.add('hidden');

        durationWrapper.classList.add('hidden');
    }
}
</script>

@endsection
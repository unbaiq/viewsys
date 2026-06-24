@extends('layouts.app')

@section('header','Upload Media')

@section('content')

<div class="h-full overflow-y-auto px-3 sm:px-4 md:px-6 py-4">
<div class="max-w-4xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-sm font-semibold text-slate-900">Upload Media</h2>
    <p class="text-xs text-slate-500">Add images or videos</p>
</div>

<form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data" class="p-4 space-y-5">
@csrf

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
@if(auth()->user()->role === 'superadmin')
<div>
    <label class="text-xs font-medium">Company</label>
    <select name="company_id" class="mt-1 w-full border rounded px-2 py-1.5 text-xs">
        <option value="">Select Company</option>
        @foreach($companies as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>
@endif

{{-- NAME --}}
<div>
    <label class="text-xs font-medium">Media Name</label>
    <input type="text" name="name" id="mediaName"
        class="mt-1 w-full border rounded px-2 py-1.5 text-xs"
        placeholder="Auto-filled from file">
</div>

{{-- FILE INPUT --}}
<div>
    <label class="text-xs font-medium">Upload File</label>

    <div id="dropArea"
        class="mt-1 border-2 border-dashed border-slate-300 rounded-lg p-6 text-center cursor-pointer bg-slate-50 hover:bg-slate-100 transition">

        <p class="text-xs text-slate-500">Click or drag file here</p>
        <input type="file" name="file" id="fileInput" class="hidden" required>
    </div>

    <p class="text-[11px] text-slate-500 mt-1">
        JPG, PNG, WEBP, MP4, MOV, WEBM
    </p>
</div>

{{-- PREVIEW --}}
<div id="previewWrapper" class="hidden">
    <label class="text-xs font-medium">Preview</label>

    <div class="mt-2 border rounded p-3 bg-slate-50">
        <img id="imagePreview" class="hidden max-h-48 mx-auto rounded">
        <video id="videoPreview" controls class="hidden max-h-48 mx-auto rounded"></video>
    </div>
</div>

{{-- DISPLAY DURATION --}}
<div id="durationWrapper" class="hidden">
    <label class="text-xs font-medium">Display Duration (seconds)</label>
    <input type="number" name="display_duration" id="displayDuration"
        class="mt-1 w-full border rounded px-2 py-1.5 text-xs"
        value="10" min="1">
</div>

<!-- ACTIONS -->
<div class="flex justify-end gap-2 pt-3 border-t">
    <a href="{{ route('media.index') }}" class="px-3 py-1.5 text-xs border rounded">
        Cancel
    </a>

    <button class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded">
        Upload
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

// Open file picker
dropArea.addEventListener('click', () => fileInput.click());

// Drag support
dropArea.addEventListener('dragover', e => {
    e.preventDefault();
    dropArea.classList.add('bg-indigo-50');
});

dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('bg-indigo-50');
});

dropArea.addEventListener('drop', e => {
    e.preventDefault();
    dropArea.classList.remove('bg-indigo-50');
    fileInput.files = e.dataTransfer.files;
    handleFile(e.dataTransfer.files[0]);
});

fileInput.addEventListener('change', e => {
    handleFile(e.target.files[0]);
});

function handleFile(file) {
    if(!file) return;

    // Auto name
    if(!nameField.value){
        nameField.value = file.name.split('.').slice(0, -1).join('.');
    }

    previewWrapper.classList.remove('hidden');

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
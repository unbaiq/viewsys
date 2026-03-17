@extends('layouts.app')

@section('header','Upload Media')

@section('content')

<div class="max-w-xl mx-auto">

<div class="bg-white border rounded-xl shadow-sm p-8">

<h2 class="text-lg font-semibold mb-6">
Upload Media
</h2>


{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
{{ session('success') }}
</div>
@endif


{{-- ERROR MESSAGE --}}
@if ($errors->any())
<div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm">
<ul class="space-y-1">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif


<form method="POST"
action="{{ route('media.store') }}"
enctype="multipart/form-data"
class="space-y-5">

@csrf


{{-- COMPANY SELECTOR FOR SUPERADMIN --}}
@if(auth()->user()->role === 'superadmin')

<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
Company
</label>

<select
name="company_id"
required
class="w-full border rounded-lg p-3">

<option value="">Select Company</option>

@foreach($companies as $id => $name)

<option value="{{ $id }}"
{{ old('company_id') == $id ? 'selected' : '' }}>
{{ $name }}
</option>

@endforeach

</select>

</div>

@endif


{{-- FILE UPLOAD --}}
<div>

<label class="block text-sm font-medium text-gray-700 mb-1">
Media File
</label>

<input
type="file"
name="file"
required
class="w-full border rounded-lg p-3">

<p class="text-xs text-gray-400 mt-1">
Supported: JPG, PNG, WEBP, MP4, MOV, WEBM
</p>

</div>


<button
type="submit"
class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg">
Upload Media
</button>

</form>

</div>

</div>

@endsection
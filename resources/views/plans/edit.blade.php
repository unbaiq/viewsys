@extends('layouts.app')

@section('header','Edit Plan')

@section('content')

<div class="max-w-8xl mx-auto">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Edit Plan</h2>
    <p class="text-xs text-slate-500">Update pricing and limits</p>
</div>

<!-- FORM -->
<form method="POST" action="{{ route('plans.update',$plan) }}" class="p-5">
@csrf
@method('PUT')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- NAME -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Plan Name</label>
    <input name="name"
        value="{{ old('name',$plan->name) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">

    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- PRICE -->
<div>
    <label class="text-xs font-medium text-slate-600">Monthly Price (₹)</label>
    <input name="price"
        value="{{ old('price',$plan->price) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('price') border-red-500 @enderror">

    @error('price')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- STATUS -->
<div>
    <label class="text-xs font-medium text-slate-600">Status</label>
    <select name="is_active"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

        <option value="1" {{ old('is_active',$plan->is_active)=='1'?'selected':'' }}>Active</option>
        <option value="0" {{ old('is_active',$plan->is_active)=='0'?'selected':'' }}>Disabled</option>

    </select>
</div>

<!-- SCREEN -->
<div>
    <label class="text-xs font-medium text-slate-600">Screen Limit</label>
    <input name="screen_limit"
        value="{{ old('screen_limit',$plan->screen_limit) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- STORAGE -->
<div>
    <label class="text-xs font-medium text-slate-600">Storage (MB)</label>
    <input name="storage_limit"
        value="{{ old('storage_limit',$plan->storage_limit) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- FEATURES -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Features</label>
    <input name="features"
        value="{{ old('features', implode(',', $plan->features ?? [])) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

    <p class="text-[11px] text-slate-500 mt-1">
        Separate features with commas
    </p>
</div>

</div>

<!-- ACTIONS -->
<div class="flex justify-end gap-2 mt-5 border-t pt-4">

    <a href="{{ route('plans.index') }}"
       class="px-3 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        Cancel
    </a>

    <button
        class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Update
    </button>

</div>

</form>

</div>

</div>

@endsection
@extends('layouts.app')

@section('header','Create Company')

@section('content')

<div class="max-w-8xl mx-auto pb-20"> <!-- ✅ scroll safe -->

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Create Company</h2>
    <p class="text-xs text-slate-500">Add new company details</p>
</div>

<!-- FORM -->
<form method="POST" action="{{ route('companies.store') }}" class="p-5">
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- COMPANY NAME -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Company Name</label>
    <input name="name" value="{{ old('name') }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>



<!-- EMAIL -->
<div>
    <label class="text-xs font-medium text-slate-600">Email</label>
    <input type="email" name="email" value="{{ old('email') }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
    @error('email')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- PHONE -->
<div>
    <label class="text-xs font-medium text-slate-600">Phone</label>
    <input name="phone" value="{{ old('phone') }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- WEBSITE -->
<div>
    <label class="text-xs font-medium text-slate-600">Website</label>
    <input name="website"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- INDUSTRY -->
<div>
    <label class="text-xs font-medium text-slate-600">Industry</label>
    <input name="industry"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- GST -->
<div>
    <label class="text-xs font-medium text-slate-600">GST Number</label>
    <input name="gst_number"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- PAN -->
<div>
    <label class="text-xs font-medium text-slate-600">PAN Number</label>
    <input name="pan_number"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- ADDRESS -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Address</label>
    <input name="address"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- CITY -->
<div>
    <label class="text-xs font-medium text-slate-600">City</label>
    <input name="city"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- STATE -->
<div>
    <label class="text-xs font-medium text-slate-600">State</label>
    <input name="state"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- COUNTRY -->
<div>
    <label class="text-xs font-medium text-slate-600">Country</label>
    <input name="country"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- ZIP -->
<div>
    <label class="text-xs font-medium text-slate-600">Zip Code</label>
    <input name="zip_code"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- PLAN -->
<div>
    <label class="text-xs font-medium text-slate-600">Plan</label>
    <select name="plan"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
        <option value="starter">Starter</option>
        <option value="business">Business</option>
        <option value="enterprise">Enterprise</option>
    </select>
</div>

<!-- SCREEN LIMIT -->
<div>
    <label class="text-xs font-medium text-slate-600">Screen Limit</label>
    <input name="screen_limit" value="{{ old('screen_limit',5) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- STORAGE -->
<div>
    <label class="text-xs font-medium text-slate-600">Storage (MB)</label>
    <input name="storage_limit" value="{{ old('storage_limit',10240) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- USER LIMIT -->
<div>
    <label class="text-xs font-medium text-slate-600">User Limit</label>
    <input name="user_limit" value="{{ old('user_limit',5) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- START DATE -->
<div>
    <label class="text-xs font-medium text-slate-600">Start Date</label>
    <input type="date" name="plan_start_date"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- END DATE -->
<div>
    <label class="text-xs font-medium text-slate-600">End Date</label>
    <input type="date" name="plan_end_date"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>


<!-- STATUS -->
<div class="md:col-span-2 flex gap-4">
    <label class="text-xs">
        <input type="checkbox" name="is_active" value="1" checked> Active
    </label>

    <label class="text-xs">
        <input type="checkbox" name="is_trial" value="1"> Trial
    </label>
</div>

</div>

<!-- ACTIONS -->
<div class="flex justify-end gap-2 mt-5 border-t pt-4">

    <a href="{{ route('companies.index') }}"
       class="px-3 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        Cancel
    </a>

    <button
        class="px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Create
    </button>

</div>

</form>

</div>

</div>

@endsection
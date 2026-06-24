@extends('layouts.app')

@section('header','Edit Company')

@section('content')

<div class="max-w-8xl mx-auto pb-20">

<div class="bg-white border border-slate-200 rounded-xl shadow-sm">

<!-- HEADER -->
<div class="px-5 py-4 border-b">
    <h2 class="text-base font-semibold text-slate-900">Edit Company</h2>
    <p class="text-xs text-slate-500">Update company details</p>
</div>

<!-- FORM -->
<form method="POST" action="{{ route('companies.update', $company) }}" class="p-5">
@csrf
@method('PUT')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- COMPANY NAME -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Company Name</label>
    <input name="name"
        value="{{ old('name', $company->name) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- EMAIL -->
<div>
    <label class="text-xs font-medium text-slate-600">Email</label>
    <input type="email" name="email"
        value="{{ old('email', $company->email) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- PHONE -->
<div>
    <label class="text-xs font-medium text-slate-600">Phone</label>
    <input name="phone"
        value="{{ old('phone', $company->phone) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- WEBSITE -->
<div>
    <label class="text-xs font-medium text-slate-600">Website</label>
    <input name="website"
        value="{{ old('website', $company->website) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- INDUSTRY -->
<div>
    <label class="text-xs font-medium text-slate-600">Industry</label>
    <input name="industry"
        value="{{ old('industry', $company->industry) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- GST -->
<div>
    <label class="text-xs font-medium text-slate-600">GST Number</label>
    <input name="gst_number"
        value="{{ old('gst_number', $company->gst_number) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- PAN -->
<div>
    <label class="text-xs font-medium text-slate-600">PAN Number</label>
    <input name="pan_number"
        value="{{ old('pan_number', $company->pan_number) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- ADDRESS -->
<div class="md:col-span-2">
    <label class="text-xs font-medium text-slate-600">Address</label>
    <input name="address"
        value="{{ old('address', $company->address) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- CITY -->
<div>
    <label class="text-xs font-medium text-slate-600">City</label>
    <input name="city"
        value="{{ old('city', $company->city) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- STATE -->
<div>
    <label class="text-xs font-medium text-slate-600">State</label>
    <input name="state"
        value="{{ old('state', $company->state) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- COUNTRY -->
<div>
    <label class="text-xs font-medium text-slate-600">Country</label>
    <input name="country"
        value="{{ old('country', $company->country) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- ZIP -->
<div>
    <label class="text-xs font-medium text-slate-600">Zip Code</label>
    <input name="zip_code"
        value="{{ old('zip_code', $company->zip_code) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">
</div>

<!-- PLAN -->
<div>
    <label class="text-xs font-medium text-slate-600">Plan</label>
    <select name="plan"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="starter" {{ old('plan', $company->plan)=='starter'?'selected':'' }}>Starter</option>
        <option value="business" {{ old('plan', $company->plan)=='business'?'selected':'' }}>Business</option>
        <option value="enterprise" {{ old('plan', $company->plan)=='enterprise'?'selected':'' }}>Enterprise</option>
    </select>
</div>

<!-- SCREEN LIMIT -->
<div>
    <label class="text-xs font-medium text-slate-600">Screen Limit</label>
    <input name="screen_limit"
        value="{{ old('screen_limit', $company->screen_limit) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- STORAGE -->
<div>
    <label class="text-xs font-medium text-slate-600">Storage (MB)</label>
    <input name="storage_limit"
        value="{{ old('storage_limit', $company->storage_limit) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- USER LIMIT -->
<div>
    <label class="text-xs font-medium text-slate-600">User Limit</label>
    <input name="user_limit"
        value="{{ old('user_limit', $company->user_limit) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- START DATE -->
<div>
    <label class="text-xs font-medium text-slate-600">Start Date</label>
    <input type="date" name="plan_start_date"
        value="{{ old('plan_start_date', optional($company->plan_start_date)->format('Y-m-d')) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- END DATE -->
<div>
    <label class="text-xs font-medium text-slate-600">End Date</label>
    <input type="date" name="plan_end_date"
        value="{{ old('plan_end_date', optional($company->plan_end_date)->format('Y-m-d')) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<!-- STATUS -->
<div class="md:col-span-2 flex gap-4">
    <label class="text-xs">
        <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', $company->is_active) ? 'checked' : '' }}>
        Active
    </label>

    <label class="text-xs">
        <input type="checkbox" name="is_trial" value="1"
            {{ old('is_trial', $company->is_trial) ? 'checked' : '' }}>
        Trial
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
        Update
    </button>

</div>

</form>

</div>

</div>

@endsection
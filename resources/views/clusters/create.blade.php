@extends('layouts.app')

@section('header','Create Cluster')

@section('content')

<div class="w-full h-[calc(100vh-80px)] overflow-y-auto px-4 md:px-6">

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">

        <div class="px-5 py-4 border-b">
            <h2 class="text-lg font-semibold">Create Cluster</h2>
            <p class="text-sm text-slate-500">
                Create screen clusters and assign layout zones with media.
            </p>
        </div>

        <form method="POST"
              action="{{ route('clusters.store') }}"
              class="p-5">

            @csrf

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-5">

                @if(auth()->user()->role === 'superadmin')
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">
                        Company
                    </label>

                    <select
                        name="company_id"
                        id="companySelect"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">
                            Select Company
                        </option>

                        @foreach($companies as $company)
                            <option
                                value="{{ $company->id }}"
                                {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach

                    </select>
                </div>
                @endif

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">
                        Cluster Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full border rounded-lg px-3 py-2">

                </div>

               

            </div>

           
{{-- Layouts --}}
<div class="mt-6">

<label class="block font-medium mb-3 text-sm sm:text-base">
Screen Layout
</label>

@php
$layout = old('layout','fullscreen');
@endphp

<!-- SCROLLABLE ON MOBILE -->
<div class="overflow-x-auto">
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4 sm:gap-6 min-w-[600px] sm:min-w-0">

@foreach([
'fullscreen' => 'Full Screen',
'half' => 'Half Split',
'sidebar' => 'Sidebar',
'ticker' => 'Ticker',
'grid' => '4 Grid',
'header' => 'Header',
'triple' => 'Triple',
'menu' => 'Menu Board'
] as $key => $label)

<label class="cursor-pointer">
<input type="radio" name="layout" value="{{ $key }}"
class="peer hidden layoutRadio"
{{ $layout==$key?'checked':'' }}>

<div class="border rounded-xl p-2 sm:p-3 peer-checked:border-blue-600 peer-checked:ring-2 transition">

<!-- PREVIEW -->
<div class="aspect-video rounded mb-2 overflow-hidden">

@if($key=='fullscreen')
<div class="bg-gray-300 w-full h-full"></div>

@elseif($key=='half')
<div class="flex h-full">
<div class="w-1/2 bg-gray-300"></div>
<div class="w-1/2 bg-gray-400"></div>
</div>

@elseif($key=='sidebar')
<div class="flex h-full">
<div class="w-3/4 bg-gray-300"></div>
<div class="w-1/4 bg-gray-500"></div>
</div>

@elseif($key=='ticker')
<div class="flex flex-col h-full">
<div class="flex-1 bg-gray-300"></div>
<div class="h-5 bg-gray-600"></div>
</div>

@elseif($key=='grid')
<div class="grid grid-cols-2 gap-1 h-full">
<div class="bg-gray-300"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-400"></div>
<div class="bg-gray-300"></div>
</div>

@elseif($key=='header')
<div class="flex flex-col h-full">
<div class="h-5 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>

@elseif($key=='triple')
<div class="flex h-full">
<div class="w-1/3 bg-gray-300"></div>
<div class="w-1/3 bg-gray-400"></div>
<div class="w-1/3 bg-gray-500"></div>
</div>

@elseif($key=='menu')
<div class="flex h-full">
<div class="w-1/4 bg-gray-500"></div>
<div class="flex-1 bg-gray-300"></div>
</div>
@endif

</div>

<p class="text-center text-xs sm:text-sm">{{ $label }}</p>

</div>
</label>

@endforeach

</div>
</div>

@error('layout')
<p class="text-red-500 text-sm mt-2">{{ $message }}</p>
@enderror

</div>


            <div id="headerField"
                 class="hidden mt-6">

                <label class="block text-sm font-medium mb-1">
                    Header Text
                </label>

                <input
                    type="text"
                    name="header_text"
                    value="{{ old('header_text') }}"
                    class="w-full border rounded-lg px-3 py-2">

            </div>

            <div id="tickerField"
                 class="hidden mt-6">

                <label class="block text-sm font-medium mb-1">
                    Ticker Text
                </label>

                <textarea
                    name="ticker_text"
                    rows="3"
                    class="w-full border rounded-lg px-3 py-2">{{ old('ticker_text') }}</textarea>

            </div>

            <div id="layoutMediaContainer"
                 class="mt-8"></div>

            <div class="mt-8">

                <label class="block text-sm font-medium mb-1">
                    Assign Screens
                </label>

                <select
                    multiple
                    id="screenSelect"
                    name="screens[]"
                    class="w-full border rounded-lg h-40">

                    @foreach($screens as $screen)
                        <option value="{{ $screen->id }}">
                            {{ $screen->name }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="flex justify-end gap-3 mt-8 border-t pt-5">

                <a href="{{ route('clusters.index') }}"
                   class="px-5 py-2 border rounded-lg">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg">
                    Create Cluster
                </button>

            </div>

        </form>

    </div>

</div>

<script>
const mediaOptions = `
<option value="">Select Media</option>
@foreach($media ?? [] as $item)
<option value="{{ $item->id }}">
    {{ $item->name }} ({{ ucfirst($item->type) }})
</option>
@endforeach
`;

function zone(title,key)
{
    return `
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
            <label class="block font-medium text-sm mb-2">
                ${title}
            </label>

            <select
                multiple
                name="media_sections[${key}][]"
                class="w-full border rounded-lg h-40 px-2 py-2">

                ${mediaOptions}

            </select>

            <p class="text-xs text-slate-500 mt-2">
                Hold CTRL/CMD to select multiple media items.
            </p>
        </div>
    `;
}

function renderMediaSections(layout)
{
let html = '';

switch(layout)
{
    case 'fullscreen':

        html = `
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-700">
                Full Screen content will be managed by Schedule.
            </p>
        </div>`;
        break;

    case 'half':

        html = `
        <div class="grid md:grid-cols-1 gap-4">
            ${zone('Right Zone Media','right')}
        </div>`;
        break;

    case 'sidebar':

        html = `
        <div class="grid md:grid-cols-1 gap-4">
            ${zone('Sidebar Media','sidebar')}
        </div>`;
        break;

    case 'header':

        html = `
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-green-700">
                Header content will be taken from Header Text.
            </p>
        </div>`;
        break;

    case 'ticker':

        html = `
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-green-700">
                Ticker content will be taken from Ticker Text.
            </p>
        </div>`;
        break;

    case 'triple':

        html = `
        <div class="grid md:grid-cols-2 gap-4">
            ${zone('Center Zone Media','center')}
            ${zone('Right Zone Media','right')}
        </div>`;
        break;

    case 'grid':

        html = `
        <div class="grid md:grid-cols-2 gap-4">
            ${zone('Top Right Media','top_right')}
            ${zone('Bottom Left Media','bottom_left')}
            ${zone('Bottom Right Media','bottom_right')}
        </div>`;
        break;

    case 'menu':

        html = `
        <div class="grid md:grid-cols-1 gap-4">
            ${zone('Left Menu Media','left')}
        </div>`;
        break;
}

document.getElementById('layoutMediaContainer').innerHTML = html;


}


function toggleLayoutFields()
{
    const layout = document.querySelector(
        'input[name="layout"]:checked'
    )?.value;

    document.getElementById('headerField')
        .classList.add('hidden');

    document.getElementById('tickerField')
        .classList.add('hidden');

    if(layout === 'header')
    {
        document.getElementById('headerField')
            .classList.remove('hidden');
    }

    if(layout === 'ticker')
    {
        document.getElementById('tickerField')
            .classList.remove('hidden');
    }
}

document.querySelectorAll('.layoutRadio')
.forEach(el => {

    el.addEventListener('change', function(){

        toggleLayoutFields();

        renderMediaSections(this.value);

    });

});

toggleLayoutFields();

renderMediaSections(
    document.querySelector(
        'input[name="layout"]:checked'
    ).value
);

document.getElementById('companySelect')
?.addEventListener('change', function(){

    const companyId = this.value;

    if(!companyId) return;

    fetch(`/get-screens/${companyId}`)
    .then(res => res.json())
    .then(data => {

        let html = '';

        data.forEach(screen => {

            html += `
            <option value="${screen.id}">
                ${screen.name}
            </option>`;
        });

        document.getElementById('screenSelect').innerHTML = html;
    });
});
</script>


@endsection



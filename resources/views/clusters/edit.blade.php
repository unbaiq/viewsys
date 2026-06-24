@extends('layouts.app')

@section('header','Edit Cluster')

@section('content')


<div class="w-full h-[calc(100vh-80px)] overflow-y-auto px-3 sm:px-4 md:px-6 lg:px-8 mx-auto">
<div class="bg-white border border-slate-200 rounded-xl shadow-sm">
<!-- HEADER -->
<div class="px-4 sm:px-5 py-4 border-b">
    <h2 class="text-sm sm:text-base font-semibold text-slate-900">Edit Cluster</h2>
    <p class="text-xs text-slate-500">Update cluster & layout</p>
</div>

<form method="POST" action="{{ route('clusters.update',$cluster) }}" class="p-4 sm:p-5">
@csrf
@method('PUT')

<!-- GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

{{-- COMPANY --}}
@if(auth()->user()->role === 'superadmin')
<div class="sm:col-span-2">
    <label class="text-xs font-medium text-slate-600">Company</label>
    <select name="company_id" id="companySelect"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">

        <option value="">Select Company</option>

        @foreach($companies as $company)
            <option value="{{ $company->id }}"
                {{ $cluster->company_id == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
            </option>
        @endforeach

    </select>
</div>
@endif

{{-- NAME --}}
<div class="sm:col-span-2">
    <label class="text-xs font-medium text-slate-600">Cluster Name</label>
    <input name="name"
        value="{{ old('name',$cluster->name) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

</div>


{{-- Layouts --}}
<div class="mt-6">

<label class="block font-medium mb-3 text-sm sm:text-base">
Screen Layout
</label>

@php
$layout = old('layout', $cluster->type ?? 'fullscreen');
@endphp

<div class="overflow-x-auto">
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6 min-w-[600px] sm:min-w-0">

@foreach([
'fullscreen'=>'Full Screen',
'half'=>'Half Split',
'sidebar'=>'Sidebar',
'ticker'=>'Ticker',
'grid'=>'4 Grid',
'header'=>'Header',
'triple'=>'Triple',
'menu'=>'Menu Board'
] as $key=>$label)

<label class="cursor-pointer">
<input type="radio" name="layout" value="{{ $key }}"
class="peer hidden layoutRadio"
{{ $layout==$key?'checked':'' }}>

<div class="border rounded-xl p-2 sm:p-3 peer-checked:border-indigo-600 peer-checked:ring-2 transition">

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

</div>


{{-- CONDITIONAL FIELDS --}}
<div id="headerField" class="hidden mt-4">
    <label class="text-xs text-slate-600">Header Text</label>
    <input type="text" name="header_text"
        value="{{ old('header_text',$cluster->header_text) }}"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
</div>

<div id="tickerField" class="hidden mt-4">
    <label class="text-xs text-slate-600">Ticker Text</label>
    <textarea name="ticker_text" rows="2"
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('ticker_text',$cluster->ticker_text) }}</textarea>
</div>
{{-- LAYOUT MEDIA --}}
@php
$layoutMedia = [];

foreach($cluster->layouts as $layoutRow) {
$layoutMedia[$layoutRow->zone_name] =
$layoutRow->media->pluck('id')->toArray();
}
@endphp

<div id="layoutMediaContainer" class="mt-6"></div>


{{-- SCREENS --}}
<div class="mt-5">
    <label class="text-xs font-medium text-slate-600">Assign Screens</label>

    @php
    $selected = old('screens', $assignedScreens ?? []);
    @endphp

    <select name="screens[]" id="screenSelect" multiple
        class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm h-28 sm:h-32">

        @foreach($screens as $screen)
            <option value="{{ $screen->id }}"
                {{ in_array($screen->id,$selected) ? 'selected' : '' }}>
                {{ $screen->name }}
            </option>
        @endforeach

    </select>
</div>


{{-- ACTIVE --}}
<div class="flex items-center gap-2 mt-3 text-sm">
    <input type="checkbox" name="is_active" value="1"
        {{ old('is_active',$cluster->is_active) ? 'checked' : '' }}>
    <label>Active</label>
</div>


{{-- ACTIONS --}}
<div class="flex flex-col sm:flex-row justify-end gap-2 mt-6 border-t pt-4">

    <a href="{{ route('clusters.index') }}"
       class="w-full sm:w-auto text-center px-3 py-2 text-xs border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">
        Cancel
    </a>

    <button class="w-full sm:w-auto px-4 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Update
    </button>

</div>

</form>

</div>

</div>


<script>

const existingMedia = @json($layoutMedia);

const mediaOptions = `
<option value="">Select Media</option>
@foreach($media as $item)
<option value="{{ $item->id }}">
    {{ $item->name }} ({{ ucfirst($item->type) }})
</option>
@endforeach
`;

function zone(title,key,selected = [])
{
    let html = '';

    @foreach($media as $item)
        html += `
        <option value="{{ $item->id }}"
        ${selected.includes({{ $item->id }}) ? 'selected' : ''}>
            {{ $item->name }} ({{ ucfirst($item->type) }})
        </option>`;
    @endforeach

    return `
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">

            <label class="block font-medium text-sm mb-2">
                ${title}
            </label>

            <select
                multiple
                name="media_sections[${key}][]"
                class="w-full border rounded-lg h-40">

                ${html}

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
                ${zone(
                    'Right Zone Media',
                    'right',
                    existingMedia.right || []
                )}
            </div>`;
            break;

        case 'sidebar':

            html = `
            <div class="grid md:grid-cols-1 gap-4">
                ${zone(
                    'Sidebar Media',
                    'sidebar',
                    existingMedia.sidebar || []
                )}
            </div>`;
            break;

        case 'header':

            html = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700">
                    Header content will come from Header Text.
                </p>
            </div>`;
            break;

        case 'ticker':

            html = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700">
                    Ticker content will come from Ticker Text.
                </p>
            </div>`;
            break;

        case 'triple':

            html = `
            <div class="grid md:grid-cols-2 gap-4">

                ${zone(
                    'Center Zone Media',
                    'center',
                    existingMedia.center || []
                )}

                ${zone(
                    'Right Zone Media',
                    'right',
                    existingMedia.right || []
                )}

            </div>`;
            break;

        case 'grid':

            html = `
            <div class="grid md:grid-cols-2 gap-4">

                ${zone(
                    'Top Right Media',
                    'top_right',
                    existingMedia.top_right || []
                )}

                ${zone(
                    'Bottom Left Media',
                    'bottom_left',
                    existingMedia.bottom_left || []
                )}

                ${zone(
                    'Bottom Right Media',
                    'bottom_right',
                    existingMedia.bottom_right || []
                )}

            </div>`;
            break;

        case 'menu':

            html = `
            <div class="grid md:grid-cols-1 gap-4">

                ${zone(
                    'Left Menu Media',
                    'left',
                    existingMedia.left || []
                )}

            </div>`;
            break;
    }

    document.getElementById(
        'layoutMediaContainer'
    ).innerHTML = html;
}

function toggleLayoutFields()
{
    let layout =
        document.querySelector(
            'input[name="layout"]:checked'
        )?.value;

    document.getElementById(
        'headerField'
    ).classList.add('hidden');

    document.getElementById(
        'tickerField'
    ).classList.add('hidden');

    if(layout === 'header')
    {
        document.getElementById(
            'headerField'
        ).classList.remove('hidden');
    }

    if(layout === 'ticker')
    {
        document.getElementById(
            'tickerField'
        ).classList.remove('hidden');
    }

    renderMediaSections(layout);
}

document.querySelectorAll('.layoutRadio')
.forEach(el => {
    el.addEventListener(
        'change',
        toggleLayoutFields
    );
});

toggleLayoutFields();

document.getElementById('companySelect')
?.addEventListener('change', function () {

    let companyId = this.value;
    let screenSelect =
        document.getElementById('screenSelect');

    if (!companyId) return;

    fetch(`/get-screens/${companyId}`)
    .then(res => res.json())
    .then(data => {

        screenSelect.innerHTML = '';

        data.forEach(screen => {

            screenSelect.innerHTML += `
                <option value="${screen.id}">
                    ${screen.name}
                </option>
            `;
        });

    });
});

</script>


@endsection
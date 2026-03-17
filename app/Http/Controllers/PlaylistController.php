<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\Media;
use App\Models\PlaylistItem;
use App\Models\Company;

class PlaylistController extends Controller
{
    public function index()
    {
        $query = Playlist::withCount('items');

        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
        }

        $playlists = $query->latest()->paginate(10);

        return view('playlists.index', compact('playlists'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();

        return view('playlists.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        if (auth()->user()->role === 'superadmin') {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $request->validate($rules);

        $companyId = auth()->user()->role === 'superadmin'
            ? $request->company_id
            : auth()->user()->company_id;

        Playlist::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'created_by' => auth()->id()
        ]);

        return redirect()
            ->route('playlists.index')
            ->with('success', 'Playlist created successfully.');
    }

    public function show(Playlist $playlist)
    {
        if (
            auth()->user()->role !== 'superadmin' &&
            $playlist->company_id !== auth()->user()->company_id
        ) {
            abort(403);
        }

        $media = Media::where('company_id', $playlist->company_id)->get();

        $playlist->load('items.media');

        return view('playlists.show', compact('playlist', 'media'));
    }

    public function edit(Playlist $playlist)
    {
        return view('playlists.edit', compact('playlist'));
    }

    public function update(Request $request, Playlist $playlist)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $playlist->update([
            'name' => $request->name
        ]);

        return redirect()->route('playlists.index')
            ->with('success','Playlist updated');
    }

    public function destroy(Playlist $playlist)
    {
        $playlist->items()->delete();
        $playlist->delete();

        return redirect()->route('playlists.index')
            ->with('success','Playlist deleted');
    }

    public function addMedia(Request $request, Playlist $playlist)
    {
        $request->validate([
            'media_id' => 'required|exists:media,id'
        ]);

        $order = PlaylistItem::where('playlist_id',$playlist->id)->max('order');
        $order = $order ? $order + 1 : 1;

        PlaylistItem::create([
            'playlist_id' => $playlist->id,
            'media_id' => $request->media_id,
            'order' => $order
        ]);

        return back()->with('success','Media added');
    }

    public function removeMedia(PlaylistItem $item)
    {
        $item->delete();

        return back()->with('success','Media removed');
    }
}
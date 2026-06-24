<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\Media;
use App\Models\PlaylistItem;
use App\Models\Company;
use App\Models\Screen;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $query = Playlist::withCount('items');

        if (Auth::user()->role !== 'superadmin') {
            $query->where('company_id', Auth::user()->company_id);
        }

        $playlists = $query->latest()->paginate(10);

        return view('playlists.index', compact('playlists'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('playlists.create', compact('companies'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $rules = ['name' => 'required|string|max:255'];

        if (Auth::user()->role === 'superadmin') {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $request->validate($rules);

        $companyId = Auth::user()->role === 'superadmin'
            ? $request->company_id
            : Auth::user()->company_id;

        Playlist::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Playlist $playlist)
    {
        $this->authorizePlaylist($playlist);

        $media = Media::where('company_id', $playlist->company_id)->get();

        $playlist->load(['items.media']);

        return view('playlists.show', compact('playlist', 'media'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Playlist $playlist)
    {
        $this->authorizePlaylist($playlist);
        return view('playlists.edit', compact('playlist'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Playlist $playlist)
    {
        $this->authorizePlaylist($playlist);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $playlist->update([
            'name' => $request->name
        ]);

        $this->refreshScreensUsingPlaylist($playlist->id);

        return redirect()->route('playlists.index')
            ->with('success','Playlist updated');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Playlist $playlist)
    {
        $this->authorizePlaylist($playlist);

        $playlistId = $playlist->id;

        $playlist->items()->delete();
        $playlist->delete();

        $this->refreshScreensUsingPlaylist($playlistId);

        return redirect()->route('playlists.index')
            ->with('success','Playlist deleted');
    }

    /*
    |--------------------------------------------------------------------------
    | ADD MEDIA (🔥 FINAL WITH screen_id)
    |--------------------------------------------------------------------------
    */
    public function addMedia(Request $request, Playlist $playlist)
    {
        $this->authorizePlaylist($playlist);
    
        $request->validate([
            'media_id' => 'required|exists:media,id'
        ]);
    
        // ✅ Ensure media belongs to same company
        $media = Media::where('id', $request->media_id)
            ->where('company_id', $playlist->company_id)
            ->firstOrFail();
    
        DB::transaction(function () use ($playlist, $media) {
    
            // 🔢 ORDER (safe)
            $order = PlaylistItem::where('playlist_id', $playlist->id)
                ->lockForUpdate()
                ->max('order');
    
            $order = $order ? $order + 1 : 1;
    
            /*
            |--------------------------------------------------------------------------
            | GET BEST SCHEDULE (WITH COMPANY FILTER 🔥)
            |--------------------------------------------------------------------------
            */
            $schedule = Schedule::where('playlist_id', $playlist->id)
                ->where('company_id', $playlist->company_id)
                ->orderByDesc('priority')
                ->first();
    
            /*
            |--------------------------------------------------------------------------
            | TARGET RESOLUTION
            |--------------------------------------------------------------------------
            */
            $screenId  = null;
            $clusterId = null;
    
            if ($schedule) {
                if (!empty($schedule->screen_id)) {
                    $screenId = $schedule->screen_id;
                } elseif (!empty($schedule->cluster_id)) {
                    $clusterId = $schedule->cluster_id;
                }
            }
    
            /*
            |--------------------------------------------------------------------------
            | CREATE ITEM (🔥 FIXED)
            |--------------------------------------------------------------------------
            */
            PlaylistItem::create([
                'company_id' => $playlist->company_id, // ✅ CRITICAL FIX
    
                'playlist_id' => $playlist->id,
                'media_id'    => $media->id,
    
                'screen_id'   => $screenId,
                'cluster_id'  => $clusterId,
    
                'order'       => $order,
    
                'start_date'  => $schedule?->start_date,
                'end_date'    => $schedule?->end_date,
    
                'start_time'  => $schedule?->start_time,
                'end_time'    => $schedule?->end_time,
    
                'days_of_week'=> $schedule?->days_of_week,
    
                'duration'    => null,
            ]);
        });
    
        $this->refreshScreensUsingPlaylist($playlist->id);
    
        return back()->with('success','Media added with correct targeting');
    }
    /*
    |--------------------------------------------------------------------------
    | REMOVE MEDIA
    |--------------------------------------------------------------------------
    */
    public function removeMedia(PlaylistItem $item)
    {
        $this->authorizePlaylist($item->playlist);

        $playlistId = $item->playlist_id;

        $item->delete();

        $this->refreshScreensUsingPlaylist($playlistId);

        return back()->with('success','Media removed');
    }

    /*
    |--------------------------------------------------------------------------
    | SECURITY HELPER
    |--------------------------------------------------------------------------
    */
    private function authorizePlaylist($playlist)
    {
        if (
            Auth::user()->role !== 'superadmin' &&
            $playlist->company_id !== Auth::user()->company_id
        ) {
            abort(403);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REFRESH SCREENS (🔥 FIXED)
    |--------------------------------------------------------------------------
    */
    private function refreshScreensUsingPlaylist($playlistId)
    {
        $screenIds = PlaylistItem::where('playlist_id', $playlistId)
            ->whereNotNull('screen_id')
            ->pluck('screen_id')
            ->unique();

        if ($screenIds->isNotEmpty()) {
            Screen::whereIn('id', $screenIds)
                ->increment('content_version');
        }
    }
}
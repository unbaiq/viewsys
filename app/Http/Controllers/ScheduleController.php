<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Screen;
use App\Models\Playlist;
use App\Models\Company;
use App\Models\Cluster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();

        $query = Schedule::with(['screen','cluster','playlist']);

        if (!$user->hasRole('superadmin')) {
            $query->where('company_id', $user->company_id);
        }

        $schedules = $query->latest()->paginate(15);

        return view('schedules.index', compact('schedules'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin')) {
            return view('schedules.create', [
                'companies' => Company::all(),
                'screens'   => Screen::all(),
                'clusters'  => Cluster::all(),
                'playlists' => Playlist::all()
            ]);
        }

        return view('schedules.create', [
            'screens'   => Screen::where('company_id', $user->company_id)->get(),
            'clusters'  => Cluster::where('company_id', $user->company_id)->get(),
            'playlists' => Playlist::where('company_id', $user->company_id)->get()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'company_id'  => 'nullable|exists:companies,id',
            'screen_id'   => 'nullable|exists:screens,id',
            'cluster_id'  => 'nullable|exists:clusters,id',
            'playlist_id' => 'required|exists:playlists,id',

            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',

            'start_time'  => 'nullable',
            'end_time'    => 'nullable',

            'days_of_week'=> 'nullable|array',
            'priority'    => 'nullable|integer|min:1|max:10',
            'is_default'  => 'nullable|boolean'
        ]);

        return DB::transaction(function () use ($request, $user) {

            $companyId = $user->hasRole('superadmin')
                ? ($request->company_id ?? abort(400, 'Company required'))
                : $user->company_id;

            // 🎯 TARGET VALIDATION
            if (!$request->screen_id && !$request->cluster_id && !$request->boolean('is_default')) {
                return back()->withErrors(['target' => 'Select screen, cluster or broadcast']);
            }

            if ($request->boolean('is_default') && ($request->screen_id || $request->cluster_id)) {
                return back()->withErrors(['target' => 'Broadcast cannot have screen or cluster']);
            }

            // ✅ SCREEN VALIDATION
            if ($request->screen_id) {
                $screen = Screen::findOrFail($request->screen_id);
                if ($screen->company_id != $companyId) {
                    abort(403, 'Invalid screen');
                }
            }

            // ✅ CLUSTER VALIDATION
            if ($request->cluster_id) {
                $cluster = Cluster::findOrFail($request->cluster_id);
                if ($cluster->company_id != $companyId) {
                    abort(403, 'Invalid cluster');
                }
            }

            // ✅ PLAYLIST VALIDATION
            $playlist = Playlist::findOrFail($request->playlist_id);
            if ($playlist->company_id != $companyId) {
                abort(403, 'Invalid playlist');
            }

            // ✅ FIX DAYS FORMAT (FULL NAME)
            $days = $request->days_of_week
                ? array_map(fn($d) => strtolower($d), $request->days_of_week)
                : null;

            // ✅ SINGLE DEFAULT PER COMPANY
            if ($request->boolean('is_default')) {
                Schedule::where('company_id', $companyId)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            Schedule::create([
                'company_id'   => $companyId,
                'screen_id'    => $request->boolean('is_default') ? null : $request->screen_id,
                'cluster_id'   => $request->boolean('is_default') ? null : $request->cluster_id,
                'playlist_id'  => $request->playlist_id,

                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,

                'start_time'   => $request->start_time,
                'end_time'     => $request->end_time,

                'days_of_week' => $days,

                'priority'     => $request->priority ?? 1,
                'is_default'   => $request->boolean('is_default'),
            ]);

            return redirect()
                ->route('schedules.index')
                ->with('success','Schedule created successfully');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Schedule $schedule)
    {
        $user = Auth::user();

        if (!$user->hasRole('superadmin') &&
            $schedule->company_id != $user->company_id) {
            abort(403);
        }

        $screens = $user->hasRole('superadmin')
            ? Screen::all()
            : Screen::where('company_id', $schedule->company_id)->get();

        $clusters = $user->hasRole('superadmin')
            ? Cluster::all()
            : Cluster::where('company_id', $schedule->company_id)->get();

        $playlists = $user->hasRole('superadmin')
            ? Playlist::all()
            : Playlist::where('company_id', $schedule->company_id)->get();

        return view('schedules.edit', compact(
            'schedule',
            'screens',
            'clusters',
            'playlists'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Schedule $schedule)
    {
        $user = Auth::user();

        if (!$user->hasRole('superadmin') &&
            $schedule->company_id != $user->company_id) {
            abort(403);
        }

        $request->validate([
            'screen_id'   => 'nullable|exists:screens,id',
            'cluster_id'  => 'nullable|exists:clusters,id',
            'playlist_id' => 'required|exists:playlists,id',

            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',

            'start_time'  => 'nullable',
            'end_time'    => 'nullable',

            'days_of_week'=> 'nullable|array',
            'priority'    => 'nullable|integer|min:1|max:10',
            'is_default'  => 'nullable|boolean'
        ]);

        return DB::transaction(function () use ($request, $schedule) {

            // ❌ INVALID COMBO
            if ($request->boolean('is_default') && ($request->screen_id || $request->cluster_id)) {
                return back()->withErrors(['target' => 'Broadcast cannot have screen or cluster']);
            }

            // MUST HAVE TARGET
            if (!$request->screen_id && !$request->cluster_id && !$request->boolean('is_default')) {
                return back()->withErrors(['target' => 'Select screen, cluster or broadcast']);
            }

            // ✅ FIX DAYS
            $days = $request->days_of_week
                ? array_map(fn($d) => strtolower($d), $request->days_of_week)
                : null;

            // ✅ SINGLE DEFAULT
            if ($request->boolean('is_default')) {
                Schedule::where('company_id', $schedule->company_id)
                    ->where('id', '!=', $schedule->id)
                    ->update(['is_default' => false]);
            }

            $schedule->update([
                'screen_id'   => $request->boolean('is_default') ? null : $request->screen_id,
                'cluster_id'  => $request->boolean('is_default') ? null : $request->cluster_id,
                'playlist_id' => $request->playlist_id,

                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,

                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,

                'days_of_week'=> $days,

                'priority'    => $request->priority ?? 1,
                'is_default'  => $request->boolean('is_default'),
            ]);

            return redirect()
                ->route('schedules.index')
                ->with('success','Schedule updated successfully');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Schedule $schedule)
    {
        $user = Auth::user();

        if (!$user->hasRole('superadmin') &&
            $schedule->company_id != $user->company_id) {
            abort(403);
        }

        $schedule->delete();

        return back()->with('success','Schedule removed successfully');
    }
}
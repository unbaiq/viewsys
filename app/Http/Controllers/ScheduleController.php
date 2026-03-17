<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Screen;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display schedules list
     */
    public function index()
    {
        $query = Schedule::with(['screen','playlist']);

        if (Auth::user()->role !== 'superadmin') {
            $query->where('company_id', Auth::user()->company_id);
        }

        $schedules = $query->latest()->paginate(15);

        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            $screens = Screen::all();
            $playlists = Playlist::all();
        } else {
            $screens = Screen::where('company_id', $user->company_id)->get();
            $playlists = Playlist::where('company_id', $user->company_id)->get();
        }

        return view('schedules.create', compact('screens','playlists'));
    }

    /**
     * Store new schedule
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'screen_id'   => 'required|exists:screens,id',
            'playlist_id' => 'required|exists:playlists,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'start_time'  => 'nullable',
            'end_time'    => 'nullable',
            'days_of_week'=> 'nullable|array',
            'priority'    => 'nullable|integer|min:1|max:10'
        ]);

        $companyId = $user->company_id;

        // fallback for superadmin
        if (!$companyId) {
            $companyId = Screen::find($request->screen_id)->company_id;
        }

        Schedule::create([
            'company_id'   => $companyId,
            'screen_id'    => $request->screen_id,
            'playlist_id'  => $request->playlist_id,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'days_of_week' => $request->days_of_week
                ? json_encode($request->days_of_week)
                : null,
            'priority'     => $request->priority ?? 1
        ]);

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Schedule created successfully');
    }

    /**
     * Edit schedule
     */
    public function edit(Schedule $schedule)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $schedule->company_id != $user->company_id) {
            abort(403);
        }

        if ($user->role === 'superadmin') {
            $screens = Screen::all();
            $playlists = Playlist::all();
        } else {
            $screens = Screen::where('company_id', $schedule->company_id)->get();
            $playlists = Playlist::where('company_id', $schedule->company_id)->get();
        }

        return view('schedules.edit', compact('schedule','screens','playlists'));
    }

    /**
     * Update schedule
     */
    
     public function update(Request $request, Schedule $schedule)
     {
         $user = Auth::user();
 
         if ($user->role !== 'superadmin' && $schedule->company_id != $user->company_id) {
             abort(403);
         }
 
         $request->validate([
             'screen_id'   => 'required|exists:screens,id',
             'playlist_id' => 'required|exists:playlists,id',
             'start_date'  => 'nullable|date',
             'end_date'    => 'nullable|date|after_or_equal:start_date',
             'start_time'  => 'nullable',
             'end_time'    => 'nullable',
             'days_of_week'=> 'nullable|array',
             'priority'    => 'nullable|integer|min:1|max:10'
         ]);
 
         $schedule->update([
             'screen_id'   => $request->screen_id,
             'playlist_id' => $request->playlist_id,
             'start_date'  => $request->start_date ?? now()->toDateString(),
             'end_date'    => $request->end_date,
             'start_time'  => $request->start_time,
             'end_time'    => $request->end_time,
             'days_of_week'=> $request->days_of_week,
             'priority'    => $request->priority ?? 1
         ]);
 
         return redirect()
             ->route('schedules.index')
             ->with('success', 'Schedule updated successfully');
     }
 
    /**
     * Delete schedule
     */
    public function destroy(Schedule $schedule)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $schedule->company_id != $user->company_id) {
            abort(403);
        }

        $schedule->delete();

        return back()->with('success', 'Schedule removed successfully');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    /**
     * List devices
     */
    public function index()
    {
        $user = Auth::user();

        $query = Screen::with(['company','lastLog']);

        if ($user->role !== 'superadmin') {
            $query->where('company_id', $user->company_id);
        }

        $devices = $query->latest()->paginate(15);

        return view('devices.index', compact('devices'));
    }


    /**
     * Show device details
     */
    public function show(Screen $screen)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' &&
            $screen->company_id !== $user->company_id) {
            abort(403);
        }

        $logs = $screen->logs()
            ->latest()
            ->paginate(20);

        return view('devices.show', compact('screen','logs'));
    }


    /**
     * Restart device
     */
    public function restart(Screen $screen)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' &&
            $screen->company_id !== $user->company_id) {
            abort(403);
        }

        /*
        Future:
        Send command to player via websocket / mqtt
        */

        system_log('device','Restart Command Sent', $screen->name);

        return back()->with('success','Restart command sent to device');
    }
}
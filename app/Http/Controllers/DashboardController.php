<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\Company;
use App\Models\Media;
use App\Models\User;
use App\Models\Playlist;
use App\Models\Schedule;
use App\Models\SystemLog;
use App\Models\DeviceLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'superadmin') {

            $stats = [
                'companies' => Company::count(),
                'users' => User::count(),
                'screens' => Screen::count(),
                'online_screens' => Screen::where('status', 1)->count(),
                'offline_screens' => Screen::where('status', 0)->count(),
                'media' => Media::count(),
            ];

            $recentLogs = SystemLog::latest()->limit(10)->get();

            return view('dashboard.superadmin', compact('stats', 'recentLogs'));
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $stats = [
                'screens' => Screen::where('company_id', $user->company_id)->count(),
                'online_screens' => Screen::where('company_id', $user->company_id)->where('status', 1)->count(),
                'offline_screens' => Screen::where('company_id', $user->company_id)->where('status', 0)->count(),
                'media' => Media::where('company_id', $user->company_id)->count(),
                'users' => User::where('company_id', $user->company_id)->count(),
            ];

            $recentLogs = SystemLog::latest()->limit(10)->get();

            return view('dashboard.admin', compact('stats', 'recentLogs'));
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        $screen = Screen::where('id', $user->screen_id)->first();

        return view('dashboard.manager', compact('screen'));
    }

    public function adminDashboard()
    {
        $companyId = auth()->user()->company_id;

        $stats = [

            'screens' => Screen::where('company_id', $companyId)->count(),

            'online_screens' => Screen::where('company_id', $companyId)
                ->where('status', 1)->count(),

            'offline_screens' => Screen::where('company_id', $companyId)
                ->where('status', 0)->count(),

            'media' => Media::where('company_id', $companyId)->count(),

            'users' => User::where('company_id', $companyId)->count(),

            'playlists' => Playlist::where('company_id', $companyId)->count(),

            'schedules' => Schedule::where('company_id', $companyId)->count(),

        ];

        $recentLogs = SystemLog::latest()->limit(8)->get();

        return view('dashboard.admin', compact('stats', 'recentLogs'));
    }
    public function managerDashboard()
    {
        $user = auth()->user();

        $screen = Screen::where('id', $user->screen_id)->first();

        $stats = [

            'media' => Media::where('company_id', $user->company_id)->count(),

            'playlists' => Playlist::where('company_id', $user->company_id)->count(),

            'schedules' => Schedule::where('screen_id', $user->screen_id)->count(),

            'status' => $screen?->status,

            'last_seen' => $screen?->last_seen
        ];

        $recentLogs = DeviceLog::where('screen_id', $user->screen_id)
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.manager', compact('stats', 'screen', 'recentLogs'));
    }
}
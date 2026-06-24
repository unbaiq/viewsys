<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\Media;
use App\Models\Playlist;
use App\Models\Company;
use App\Models\DeviceLog;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | BASE QUERIES
        |--------------------------------------------------------------------------
        */
        $screenQuery = Screen::query();
        $mediaQuery = Media::query();
        $playlistQuery = Playlist::query();
        $logQuery = DeviceLog::query();

        // Company restriction
        if ($user->role !== 'superadmin') {
            $screenQuery->where('company_id', $user->company_id);
            $mediaQuery->where('company_id', $user->company_id);
            $playlistQuery->where('company_id', $user->company_id);

            // Optional: if logs have company_id
            if (Schema::hasColumn('device_logs', 'company_id')) {
                $logQuery->where('company_id', $user->company_id);
            }
        }

        // Manager restriction (single screen)
        if ($user->role === 'manager' && $user->screen_id) {
            $screenQuery->where('id', $user->screen_id);
            $logQuery->where('screen_id', $user->screen_id);
        }

        /*
        |--------------------------------------------------------------------------
        | SCREEN STATS
        |--------------------------------------------------------------------------
        */
        $totalScreens = (clone $screenQuery)->count();

        $onlineScreens = (clone $screenQuery)
            ->where('last_seen', '>', now()->subMinutes(2))
            ->count();

        $offlineScreens = $totalScreens - $onlineScreens;

        /*
        |--------------------------------------------------------------------------
        | MEDIA & PLAYLIST
        |--------------------------------------------------------------------------
        */
        $totalMedia = (clone $mediaQuery)->count();
        $totalPlaylists = (clone $playlistQuery)->count();

        $companies = $user->role === 'superadmin'
            ? Company::count()
            : null;

        /*
        |--------------------------------------------------------------------------
        | STORAGE USAGE
        |--------------------------------------------------------------------------
        */
        $storageUsed = (clone $mediaQuery)->sum('size');
        $storageGB = round($storageUsed / 1024 / 1024 / 1024, 2);

        /*
        |--------------------------------------------------------------------------
        | TODAY ACTIVITY (DAY REPORT 🔥)
        |--------------------------------------------------------------------------
        */
        $todayLogs = (clone $logQuery)
            ->whereDate('created_at', today());

        $todayOnline = (clone $todayLogs)
            ->where('status', 'online')
            ->count();

        $todayOffline = (clone $todayLogs)
            ->where('status', 'offline')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | HOURLY ACTIVITY (GRAPH DATA)
        |--------------------------------------------------------------------------
        */
        $hourlyRaw = (clone $logQuery)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereDate('created_at', today())
            ->groupBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        // Fill missing hours (0–23)
        $hourly = [];
        for ($i = 0; $i < 24; $i++) {
            $hourly[$i] = $hourlyRaw[$i] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | RECENT DEVICE LOGS
        |--------------------------------------------------------------------------
        */
        $recentLogs = (clone $logQuery)
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return view('analytics.index', [
            'totalScreens'   => $totalScreens,
            'onlineScreens'  => $onlineScreens,
            'offlineScreens' => $offlineScreens,
            'totalMedia'     => $totalMedia,
            'totalPlaylists' => $totalPlaylists,
            'companies'      => $companies,
            'storageGB'      => $storageGB,
            'todayOnline'    => $todayOnline,
            'todayOffline'   => $todayOffline,
            'hourly'         => array_values($hourly), // clean for chart
            'recentLogs'     => $recentLogs
        ]);
    }

}
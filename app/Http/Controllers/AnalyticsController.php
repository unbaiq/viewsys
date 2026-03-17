<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\Media;
use App\Models\Playlist;
use App\Models\Company;
use App\Models\DeviceLog;

class AnalyticsController extends Controller
{

    public function index()
    {

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Base Query Scope
        |--------------------------------------------------------------------------
        */

        $screenQuery = Screen::query();
        $mediaQuery = Media::query();
        $playlistQuery = Playlist::query();

        if($user->role !== 'superadmin'){
            $screenQuery->where('company_id',$user->company_id);
            $mediaQuery->where('company_id',$user->company_id);
            $playlistQuery->where('company_id',$user->company_id);
        }

        if($user->role === 'manager'){
            $screenQuery->where('id',$user->screen_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Metrics
        |--------------------------------------------------------------------------
        */

        $totalScreens = $screenQuery->count();

        $onlineScreens = $screenQuery
            ->clone()
            ->where('last_seen','>',now()->subMinutes(2))
            ->count();

        $offlineScreens = $totalScreens - $onlineScreens;

        $totalMedia = $mediaQuery->count();

        $totalPlaylists = $playlistQuery->count();

        $companies = $user->role === 'superadmin'
            ? Company::count()
            : null;

        /*
        |--------------------------------------------------------------------------
        | Storage Usage
        |--------------------------------------------------------------------------
        */

        $storageUsed = $mediaQuery->sum('size');

        $storageGB = round($storageUsed / 1024 / 1024 / 1024,2);

        /*
        |--------------------------------------------------------------------------
        | Device Health
        |--------------------------------------------------------------------------
        */

        $recentLogs = DeviceLog::latest()->take(10)->get();

        return view('analytics.index',[
            'totalScreens'=>$totalScreens,
            'onlineScreens'=>$onlineScreens,
            'offlineScreens'=>$offlineScreens,
            'totalMedia'=>$totalMedia,
            'totalPlaylists'=>$totalPlaylists,
            'companies'=>$companies,
            'storageGB'=>$storageGB,
            'recentLogs'=>$recentLogs
        ]);

    }

}
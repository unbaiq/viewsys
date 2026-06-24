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
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->hasRole('superadmin')){

            // STATS
            $stats = [
                'companies' => Company::count(),
                'users' => User::count(),
                'screens' => Screen::count(),
                'online_screens' => Screen::where('status', 1)->count(),
                'offline_screens' => Screen::where('status', 0)->count(),
                'media' => Media::count(),
            ];

            $chartOnline = $stats['online_screens'];
            $chartOffline = $stats['offline_screens'];

            $activityData = $this->getDailyCounts(SystemLog::class, 'created_at');
            $mediaData = $this->getDailyCounts(Media::class, 'created_at');

            $usedStorage = Media::sum('size'); // bytes
            $usedGB = $usedStorage / 1024 / 1024 / 1024;
            $totalStorage = 10240; // 10GB default
            $storagePercent = $totalStorage ? round(($usedGB / $totalStorage) * 100) : 0;

            $recentLogs = SystemLog::with('user')->latest()->limit(10)->get();

            return view('dashboard.superadmin', compact(
                'stats',
                'recentLogs',
                'chartOnline',
                'chartOffline',
                'activityData',
                'mediaData',
                'storagePercent'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->hasRole('admin')) {

            $companyId = $user->company_id;
            $onlineThreshold = now()->subMinutes(2);

            $totalScreens = Screen::where('company_id', $companyId)->count();

            $onlineScreens = Screen::where('company_id', $companyId)
                ->where('last_seen', '>=', $onlineThreshold)
                ->count();

            $offlineScreens = Screen::where('company_id', $companyId)
                ->where(function ($q) use ($onlineThreshold) {
                    $q->whereNull('last_seen')
                      ->orWhere('last_seen', '<', $onlineThreshold);
                })
                ->count();

            $stats = [
                'screens' => $totalScreens,
                'online_screens' => $onlineScreens,
                'offline_screens' => $offlineScreens,
                'media' => Media::where('company_id', $companyId)->count(),
                'users' => User::where('company_id', $companyId)->role('manager')->count(),
                'playlists' => Playlist::where('company_id', $companyId)->count(),
                'schedules' => Schedule::where('company_id', $companyId)->count(),
            ];

            $chartOnline = $onlineScreens;
            $chartOffline = $offlineScreens;

            $activityData = $this->getDailyCounts(SystemLog::class, 'created_at', $companyId);
            $mediaData = $this->getDailyCounts(Media::class, 'created_at', $companyId);

            /*
            |--------------------------------------------------------------------------
            | SCREEN STATUS REPORT
            |--------------------------------------------------------------------------
            */
            $screenStatusReport = Screen::where('company_id', $companyId)
                ->orderBy('name')
                ->get()
                ->map(function ($screen) use ($onlineThreshold) {
                    $screen->current_status = ($screen->last_seen && $screen->last_seen >= $onlineThreshold) ? 'Online' : 'Offline';
                    $screen->last_seen_human = $screen->last_seen ? $screen->last_seen->diffForHumans() : 'Never';
                    return $screen;
                });

            /*
            |--------------------------------------------------------------------------
            | DAYWISE UP/DOWN STATUS BAR CHART (7 Days to match view layout)
            |--------------------------------------------------------------------------
            */
            $daysData = [
                'labels' => [],
                'online' => [],
                'offline' => []
            ];

            // Build historical view calculation based on ping data logs or active status approximations
            for ($i = 6; $i >= 0; $i--) {
                $targetDate = now()->subDays($i);
                $dateString = $targetDate->format('Y-m-d');
                $daysData['labels'][] = $i === 0 ? 'Today' : $i . 'd';

                // Query count of screens seen communicating on that exact date
               //  This looks up the screen's company relationship correctly
$activeOnDay = DeviceLog::whereHas('screen', function ($query) use ($companyId) {
    $query->where('company_id', $companyId);
})
->whereDate('created_at', $dateString)
->distinct('screen_id')
->count('screen_id');

                // If no devices logged metrics, default baseline values to keep rendering stable
                if ($i === 0) {
                    $activeOnDay = $onlineScreens;
                }

                $daysData['online'][] = min($activeOnDay, $totalScreens);
                $daysData['offline'][] = max(0, $totalScreens - $activeOnDay);
            }

            /*
            |--------------------------------------------------------------------------
            | STORAGE & SYSTEM HEALTH
            |--------------------------------------------------------------------------
            */
            $usedStorage = Media::where('company_id', $companyId)->sum('size');
            $usedGB = $usedStorage / 1024 / 1024 / 1024;
            $totalStorage = 10240;
            $storagePercent = $totalStorage ? round(($usedGB / $totalStorage) * 100) : 0;

            $uptime = $totalScreens ? round(($onlineScreens / $totalScreens) * 100) : 0;

            /*
            |--------------------------------------------------------------------------
            | DATA LISTS
            |--------------------------------------------------------------------------
            */
            $recentLogs = SystemLog::with('user')
                ->where('company_id', $companyId)
                ->latest()
                ->limit(10)
                ->get();

            $screens = Screen::where('company_id', $companyId)->latest()->limit(10)->get();
            $managers = User::where('company_id', $companyId)->role('manager')->limit(5)->get();

            return view('dashboard.admin', compact(
                'stats',
                'chartOnline',
                'chartOffline',
                'activityData',
                'mediaData',
                'daysData',
                'screenStatusReport',
                'storagePercent',
                'uptime',
                'recentLogs',
                'screens',
                'managers'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGER DASHBOARD
        |--------------------------------------------------------------------------
        */
        $screen = Screen::find($user->screen_id);

        $stats = [
            'media' => Media::where('company_id', $user->company_id)->count(),
            'playlists' => Playlist::where('company_id', $user->company_id)->count(),
            'schedules' => Schedule::where('screen_id', $user->screen_id)->count(),
            'status' => $screen?->status,
            'last_seen' => $screen?->last_seen
        ];

        $activityData = $this->getDailyCounts(DeviceLog::class, 'created_at', null, $user->screen_id);

        $recentLogs = DeviceLog::where('screen_id', $user->screen_id)
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.manager', compact(
            'stats',
            'screen',
            'recentLogs',
            'activityData'
        ));
    }

    private function getDailyCounts($model, $column = 'created_at', $companyId = null, $screenId = null)
    {
        $query = $model::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($screenId) {
            $query->where('screen_id', $screenId);
        }

        $raw = $query
            ->selectRaw("DATE($column) as date, COUNT(*) as total")
            ->whereNotNull($column)
            ->where($column, '>=', now()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data[] = $raw[$date] ?? 0;
        }

        return $data;
    }
}
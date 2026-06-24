<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Screen;
use App\Models\Schedule;
use App\Models\DeviceLog;
use App\Models\PlaylistItem;
use Carbon\Carbon;

class PlayerController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Device
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([
            'device_id' => 'required'
        ]);

        $screen = Screen::where('device_id', $request->device_id)->first();

        if (!$screen) {
            return response()->json([
                'message' => 'Device not registered'
            ], 404);
        }

        return response()->json([
            'status' => 'authorized',
            'screen_id' => $screen->id,
            'company_id' => $screen->company_id,
            'orientation' => $screen->orientation ?? 'landscape',
            'sync_interval' => 30
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Sync
    |--------------------------------------------------------------------------
    */

    public function sync(Request $request)
    {
        $request->validate([
            'screen_id' => 'required|exists:screens,id',
            'version' => 'nullable|integer|min:0'
        ]);

        $screen = Screen::findOrFail($request->screen_id);

        $playerVersion = (int) ($request->version ?? 0);

        // Versions
        $contentVersion = (int) $screen->content_version;
        $scheduleVersion = (int) $screen->schedule_version;
        $mediaVersion = (int) $screen->media_version;

        // Change Detection
        $contentChanged = $contentVersion > $playerVersion;
        $scheduleChanged = $scheduleVersion > $playerVersion;
        $mediaChanged = $mediaVersion > $playerVersion;

        // Layout changes are tracked through content_version
        $layoutChanged = $contentChanged;

        // Device Actions
        $takeScreenshot = (bool) $screen->request_screenshot;
        $restart = (bool) $screen->restart_requested;

        // Commands
        $commands = is_array($screen->commands)
            ? $screen->commands
            : [];

        // Reset one-time flags after sending
        if ($takeScreenshot || $restart) {
            $screen->update([
                'request_screenshot' => false,
                'restart_requested' => false,
            ]);
        }

        // Clear commands after sending
        if (!empty($commands)) {
            $screen->clearCommands();
        }

        return response()->json([

            // Latest version player should save
            'version' => max(
                $contentVersion,
                $scheduleVersion,
                $mediaVersion
            ),

            // Update flags
            'content_changed' => $contentChanged,
            'layout_changed' => $layoutChanged,
            'schedule_changed' => $scheduleChanged,
            'media_changed' => $mediaChanged,

            // Actions
            'take_screenshot' => $takeScreenshot,
            'restart' => $restart,

            // Commands
            'commands' => $commands,

            // Screen settings
            'orientation' => $screen->orientation ?? 'landscape',

            // Debug (remove in production if not needed)
            'player_version' => $playerVersion,
            'content_version' => $contentVersion,
            'schedule_version' => $scheduleVersion,
            'media_version' => $mediaVersion,
        ]);
    }
    //   Media
    public function schedule(Request $request)
    {
        $request->validate([
            'screen_id' => 'required|exists:screens,id'
        ]);

        $screen = \App\Models\Screen::with('clusters')
            ->findOrFail($request->screen_id);

        $companyId = $screen->company_id;
        $clusterIds = $screen->clusters->pluck('id')->toArray();

        $cluster = $screen->clusters->first();
        $layoutType = $cluster?->type ?? 'fullscreen';

        /*
        |--------------------------------------------------------------------------
        | LOAD CLUSTER LAYOUTS
        |--------------------------------------------------------------------------
        */
        $layouts = [];

        if ($cluster) {

            $cluster->load('layouts.media');

            foreach ($cluster->layouts as $layout) {

                $layouts[$layout->zone_name] = $layout->media
                    ->map(function ($media) {

                        return [
                            'id' => $media->id,
                            'name' => $media->name,
                            'url' => $media->url,
                            'type' => $media->type,
                            'duration' => $media->final_duration ?? 10,
                        ];

                    })
                    ->values();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CURRENT DATE / TIME
        |--------------------------------------------------------------------------
        */
        $nowCarbon = now('Asia/Kolkata');
        $now = $nowCarbon->format('Y-m-d H:i:s');
        $todayFull = strtolower($nowCarbon->format('l'));
        $todayShort = strtolower($nowCarbon->format('D'));

        /*
        |--------------------------------------------------------------------------
        | ACTIVE SCHEDULES
        |--------------------------------------------------------------------------
        */
        $schedules = \App\Models\Schedule::where('company_id', $companyId)

            ->where(function ($q) use ($screen, $clusterIds) {

                $q->where('screen_id', $screen->id);

                if (!empty($clusterIds)) {
                    $q->orWhereIn('cluster_id', $clusterIds);
                }

                $q->orWhere('is_default', true);
            })

            ->where(function ($q) use ($now) {

                $q->whereRaw("
            (
                start_date IS NULL OR
                CONCAT(start_date,' ',IFNULL(start_time,'00:00:00')) <= ?
            )
        ", [$now]);

                $q->whereRaw("
            (
                end_date IS NULL OR
                CONCAT(end_date,' ',IFNULL(end_time,'23:59:59')) >= ?
            )
        ", [$now]);
            })

            ->get()

            ->filter(function ($schedule) use ($todayFull, $todayShort) {

                if (empty($schedule->days_of_week)) {
                    return true;
                }

                $days = is_array($schedule->days_of_week)
                    ? $schedule->days_of_week
                    : json_decode($schedule->days_of_week, true);

                if (!$days || !is_array($days)) {
                    return true;
                }

                $days = array_map(
                    fn($d) => strtolower(trim($d)),
                    $days
                );

                return in_array($todayFull, $days)
                    || in_array($todayShort, $days);
            })

            ->sortBy('priority')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | NO SCHEDULE FOUND
        |--------------------------------------------------------------------------
        */
        if ($schedules->isEmpty()) {

            return response()->json([

                'screen_id' => $screen->id,

                'cluster' => [
                    'id' => $cluster?->id,
                    'name' => $cluster?->name,
                    'layout_type' => $layoutType,
                    'header_text' => $cluster?->header_text,
                    'ticker_text' => $cluster?->ticker_text,
                ],

                'layouts' => $layouts,

                'playlist_ids' => [],

                'playlist' => [],

                'meta' => [
                    'server_time' => now()->toDateTimeString(),
                    'company_id' => $companyId,
                    'screen_id' => $screen->id,
                ]
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PLAYLIST IDS
        |--------------------------------------------------------------------------
        */
        $scheduleMap = $schedules->keyBy('playlist_id');

        $playlistIds = $schedules
            ->pluck('playlist_id')
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | PLAYLIST ITEMS
        |--------------------------------------------------------------------------
        */
        $items = \App\Models\PlaylistItem::with('media')

            ->where('company_id', $companyId)

            ->whereIn(
                'playlist_id',
                $playlistIds
            )

            ->where(function ($q) use ($screen, $clusterIds) {

                $q->where('screen_id', $screen->id);

                if (!empty($clusterIds)) {

                    $q->orWhere(function ($q2) use ($clusterIds) {

                        $q2->whereNull('screen_id')
                            ->whereIn('cluster_id', $clusterIds);
                    });
                }

                $q->orWhere(function ($q2) {

                    $q2->whereNull('screen_id')
                        ->whereNull('cluster_id');
                });
            })

            ->where(function ($q) use ($now) {

                $q->whereRaw("
            (
                start_date IS NULL OR
                CONCAT(start_date,' ',IFNULL(start_time,'00:00:00')) <= ?
            )
        ", [$now]);

                $q->whereRaw("
            (
                end_date IS NULL OR
                CONCAT(end_date,' ',IFNULL(end_time,'23:59:59')) >= ?
            )
        ", [$now]);
            })

            ->orderByRaw("
        CASE
            WHEN screen_id = ? THEN 1
            WHEN cluster_id IN (" . implode(',', $clusterIds ?: [0]) . ")
            THEN 2
            ELSE 3
        END
    ", [$screen->id])

            ->orderBy('order')

            ->get()

            ->filter(function ($item) use ($todayFull, $todayShort) {

                if (!$item->media) {
                    return false;
                }

                if (empty($item->days_of_week)) {
                    return true;
                }

                $days = is_array($item->days_of_week)
                    ? $item->days_of_week
                    : json_decode($item->days_of_week, true);

                if (!$days || !is_array($days)) {
                    return true;
                }

                $days = array_map(
                    fn($d) => strtolower(trim($d)),
                    $days
                );

                return in_array($todayFull, $days)
                    || in_array($todayShort, $days);
            })

            ->map(function ($item) use ($scheduleMap) {

                $media = $item->media;
                $schedule = $scheduleMap->get(
                    $item->playlist_id
                );

                $start = $schedule && $schedule->start_date
                    ? $schedule->start_date . ' ' . ($schedule->start_time ?? '00:00:00')
                    : null;

                $end = $schedule && $schedule->end_date
                    ? $schedule->end_date . ' ' . ($schedule->end_time ?? '23:59:59')
                    : null;

                $type = 'global';

                if ($schedule) {

                    if ($schedule->screen_id) {
                        $type = 'screen';
                    } elseif ($schedule->cluster_id) {
                        $type = 'cluster';
                    } elseif ($schedule->is_default) {
                        $type = 'broadcast';
                    }
                }

                return [

                    'id' => $item->id,
                    'url' => $media->url,
                    'type' => $media->type,
                    'duration' => $item->duration > 0
                        ? $item->duration
                        : ($media->final_duration ?? 10),

                    'order' => $item->order,

                    'schedule' => [

                        'start_datetime' => $start,
                        'end_datetime' => $end,
                        'days_of_week' => $schedule?->days_of_week,
                        'type' => $type,
                        'priority' => $schedule?->priority,
                    ]
                ];
            })

            ->values();

        return response()->json([

            'screen_id' => $screen->id,

            'cluster' => [
                'id' => $cluster?->id,
                'name' => $cluster?->name,
                'layout_type' => $layoutType,
                'header_text' => $cluster?->header_text,
                'ticker_text' => $cluster?->ticker_text,
            ],

            'layouts' => $layouts,

            'playlist_ids' => $playlistIds,

            'playlist' => $items,

            'meta' => [
                'server_time' => now()->toDateTimeString(),
                'company_id' => $companyId,
                'screen_id' => $screen->id,
            ]

        ]);


    }

    /*
    |--------------------------------------------------------------------------
    | Heartbeat
    |--------------------------------------------------------------------------
    */
    public function heartbeat(Request $request)
    {
        // ✅ 1. Validate
        $request->validate([
            'screen_id' => 'required|integer|exists:screens,id',
            'app_version' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 🔐 AUTH (DEVICE-BASED, NOT USER)
        |--------------------------------------------------------------------------
        */
        $screen = Screen::where('id', $request->screen_id)
            // 👉 If using API key (recommended)
            ->when($request->header('X-DEVICE-KEY'), function ($q) use ($request) {
                $q->where('api_key', $request->header('X-DEVICE-KEY'));
            })
            ->first();

        if (!$screen) {
            return response()->json([
                'ok' => false,
                'message' => 'Screen not found or unauthorized'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | 🧠 PREVENT SPAM (optional but recommended)
        |--------------------------------------------------------------------------
        */
        if ($screen->last_seen && $screen->last_seen->diffInSeconds(now()) < 5) {
            return response()->json(['ok' => true]);
        }

        /*
        |--------------------------------------------------------------------------
        | 📡 UPDATE DATA
        |--------------------------------------------------------------------------
        */
        $data = [
            'last_seen' => now(),
            'status' => true,
            'ip_address' => $request->ip(),
        ];

        if ($request->filled('app_version')) {
            $data['app_version'] = $request->app_version;
        }

        // 📍 Update location only if changed
        if ($request->filled('latitude') && $request->filled('longitude')) {
            if (
                $screen->latitude != $request->latitude ||
                $screen->longitude != $request->longitude
            ) {
                $data['latitude'] = $request->latitude;
                $data['longitude'] = $request->longitude;
                $data['location_updated_at'] = now();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 💾 SAVE
        |--------------------------------------------------------------------------
        */
        $screen->update($data);

        /*
        |--------------------------------------------------------------------------
        | 📺 OPTIONAL: SEND COMMANDS / SYNC SIGNAL
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'ok' => true,
            'server_time' => now(),
            'screen_status' => 'active',

            // 🔥 future-ready
            // 'force_sync' => $screen->content_version_changed,
            // 'commands' => ['refresh_playlist'],
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Screenshot Upload
    |--------------------------------------------------------------------------
    */

    public function screenshot(Request $request)
    {
        // ✅ 1. Validate request
        $request->validate([
            'screen_id' => 'required|integer|exists:screens,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // max 5MB
        ]);

        /*
        |--------------------------------------------------------------------------
        | 🔐 DEVICE AUTH (RECOMMENDED)
        |--------------------------------------------------------------------------
        */
        $screen = Screen::where('id', $request->screen_id)
            ->when($request->header('X-DEVICE-KEY'), function ($q) use ($request) {
                $q->where('api_key', $request->header('X-DEVICE-KEY'));
            })
            ->first();

        if (!$screen) {
            return response()->json([
                'ok' => false,
                'message' => 'Screen not found or unauthorized'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | 📂 STORE IMAGE (UNIQUE NAME)
        |--------------------------------------------------------------------------
        */
        $file = $request->file('image');

        $filename = 'screen_' . $screen->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('screenshots', $filename, 'public');

        /*
        |--------------------------------------------------------------------------
        | 💾 UPDATE SCREEN
        |--------------------------------------------------------------------------
        */
        $screen->update([
            'last_screenshot' => $path,
            'last_screenshot_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ✅ RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'ok' => true,
            'path' => $path,
            'url' => asset('storage/' . $path), // optional
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Player Logs
    |--------------------------------------------------------------------------
    */

    public function log(Request $request)
    {
        DeviceLog::create([
            'screen_id' => $request->screen_id,
            'status' => $request->type,
            'last_ping' => now(),
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'saved' => true
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Player Config
    |--------------------------------------------------------------------------
    */

    public function config()
    {
        return response()->json([
            'sync_interval' => 30,
            'download_wifi_only' => false,
            'max_storage' => "5GB"
        ]);
    }
}
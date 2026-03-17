<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Screen;
use App\Models\Schedule;
use App\Models\DeviceLog;
use Carbon\Carbon;
use Cache;

class PlayerController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Device
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $deviceId = $request->device_id ?? 'device_' . Str::uuid();
    
        $screen = Screen::firstOrCreate(
            ['device_id' => $deviceId],
            [
                'name' => $request->device_name ?? 'New Screen',
                'orientation' => $request->orientation ?? 'landscape',
                'status' => false
            ]
        );
    
        $token = Str::random(60);
    
        $screen->update([
            'device_token' => $token
        ]);
    
        return response()->json([
            'screen_id' => $screen->id,
            'device_id' => $deviceId,
            'device_token' => $token,
            'sync_interval' => 30
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Login Device
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {

        $screen = Screen::where('device_id',$request->device_id)
            ->where('device_token',$request->device_token)
            ->first();

        if(!$screen){
            return response()->json(['message'=>'Unauthorized'],401);
        }

        return response()->json([
            'status'=>'authorized',
            'screen_id'=>$screen->id,
            'company_id'=>$screen->company_id
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Sync Check
    |--------------------------------------------------------------------------
    */

    public function sync(Request $request)
    {

        $screen = Screen::find($request->screen_id);

        return response()->json([
            'version'=>$screen->content_version ?? 1,
            'schedule_changed'=>true,
            'media_changed'=>false
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Schedule
    |--------------------------------------------------------------------------
    */

    public function schedule(Request $request)
    {

        $screen_id = $request->screen_id;

        $data = Cache::remember("schedule_$screen_id",30,function() use ($screen_id){

            $now = Carbon::now();

            $schedule = Schedule::where('screen_id',$screen_id)
                ->whereDate('start_date','<=',$now)
                ->whereDate('end_date','>=',$now)
                ->orderBy('priority','desc')
                ->with('playlist.items.media')
                ->first();

            return $schedule;

        });

        return response()->json($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Media List
    |--------------------------------------------------------------------------
    */

    public function media(Request $request)
    {

        $screen_id = $request->screen_id;

        $schedule = Schedule::where('screen_id',$screen_id)
            ->with('playlist.items.media')
            ->first();

        if(!$schedule){
            return [];
        }

        $media = [];

        foreach($schedule->playlist->items as $item){

            $media[] = [
                'id'=>$item->media->id,
                'url'=>url('storage/'.$item->media->file_path),
                'size'=>$item->media->size
            ];

        }

        return response()->json([
            'media'=>$media
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Heartbeat
    |--------------------------------------------------------------------------
    */

    public function heartbeat(Request $request)
    {
        $screen = Screen::find($request->screen_id);
    
        if (!$screen) {
            return response()->json([
                'ok' => false,
                'message' => 'Screen not found'
            ], 404);
        }
    
        $screen->update([
            'last_seen' => now(),
            'status' => true,
            'ip_address' => $request->ip,
            'app_version' => $request->app_version
        ]);
    
        return response()->json([
            'ok' => true
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
            'screen_id'=>$request->screen_id,
            'status'=>$request->type,
            'message'=>$request->message
        ]);

        return response()->json([
            'saved'=>true
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
            'sync_interval'=>30,
            'download_wifi_only'=>false,
            'max_storage'=>"5GB"
        ]);
    }

}
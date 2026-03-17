<?php

use App\Models\Setting;
use App\Models\SystemLog;
use App\Models\Notification;

if (! function_exists('setting')) {

    /**
     * Get system setting
     */
    function setting($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }
}


if (! function_exists('system_log')) {

    /**
     * Save system log + create notification
     */
    function system_log($type, $action, $description = null, $meta = null)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Save System Log
        |--------------------------------------------------------------------------
        */

        SystemLog::create([
            'user_id' => $user?->id,
            'type' => $type,
            'action' => $action,
            'description' => $description,
            'meta' => $meta,
            'ip' => request()->ip(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Notification
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => null,
            'company_id' => $user?->company_id,
            'type' => $type,
            'title' => $action,
            'message' => $description ?? $action,
            'read' => false
        ]);
    }
}
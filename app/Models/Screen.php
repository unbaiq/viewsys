<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Screen extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'device_id',
        'device_token',
        'token_generated_at',

        'location',
        'orientation',
        'status',

        'content_version',
        'schedule_version',
        'media_version',
        'layout_version',

        'commands',
        'request_screenshot',
        'restart_requested',

        'ip_address',
        'app_version',
        'device_model',

        'storage_free',
        'battery_level',
        'is_charging',

        'latitude',
        'longitude',
        'location_updated_at',

        'last_seen',

        'last_screenshot',
        'last_screenshot_at',
    ];

    protected $casts = [
        'commands' => 'array',

        'request_screenshot' => 'boolean',
        'restart_requested' => 'boolean',

        'last_seen' => 'datetime',
        'last_screenshot_at' => 'datetime',
        'location_updated_at' => 'datetime',
        'token_generated_at' => 'datetime',

        'content_version' => 'integer',
        'schedule_version' => 'integer',
        'media_version' => 'integer',
        'layout_version' => 'integer',

        'battery_level' => 'integer',
        'is_charging' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function logs()
    {
        return $this->hasMany(DeviceLog::class);
    }

    public function lastLog()
    {
        return $this->hasOne(DeviceLog::class)->latestOfMany();
    }

    public function clusters()
    {
        return $this->belongsToMany(
            Cluster::class,
            'cluster_screen'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isOnline($minutes = 2)
    {
        return $this->last_seen &&
            $this->last_seen->gt(
                now()->subMinutes($minutes)
            );
    }

    /*
    |--------------------------------------------------------------------------
    | VERSION HELPERS
    |--------------------------------------------------------------------------
    */

    public function bumpContentVersion()
    {
        $this->increment('content_version');
    }

    public function bumpScheduleVersion()
    {
        $this->increment('schedule_version');
    }

    public function bumpMediaVersion()
    {
        $this->increment('media_version');
    }

    public function bumpLayoutVersion()
    {
        $this->increment('layout_version');
    }

    /*
    |--------------------------------------------------------------------------
    | DEVICE ACTION HELPERS
    |--------------------------------------------------------------------------
    */

    public function requestScreenshot()
    {
        $this->update([
            'request_screenshot' => true
        ]);
    }

    public function requestRestart()
    {
        $this->update([
            'restart_requested' => true
        ]);
    }

    public function screenshotTaken($path)
    {
        $this->update([
            'request_screenshot' => false,
            'last_screenshot' => $path,
            'last_screenshot_at' => now(),
        ]);
    }

    public function restartHandled()
    {
        $this->update([
            'restart_requested' => false
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADVANCED COMMAND SYSTEM
    |--------------------------------------------------------------------------
    */

    protected $allowedCommands = [
        'restart',
        'screenshot',
        'clear_cache',
        'update',
        'refresh_playlist',
        'show_message',
    ];

    public function pushCommand(
        string $type,
        array $payload = [],
        ?string $expiresAt = null
    ) {
        if (!in_array($type, $this->allowedCommands)) {
            return;
        }

        $commands = is_array($this->commands)
            ? $this->commands
            : [];

        $commands[] = [
            'id' => uniqid('cmd_'),
            'type' => $type,
            'payload' => $payload,
            'created_at' => now()->toDateTimeString(),
            'expires_at' => $expiresAt,
        ];

        $this->update([
            'commands' => $commands
        ]);
    }

    public function getValidCommands()
    {
        return collect($this->commands ?? [])
            ->filter(function ($cmd) {

                if (!empty($cmd['expires_at'])) {
                    return now()->lt(
                        $cmd['expires_at']
                    );
                }

                return true;
            })
            ->values()
            ->toArray();
    }

    public function clearCommands()
    {
        $this->update([
            'commands' => []
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MULTI TARGET HELPERS
    |--------------------------------------------------------------------------
    */

    public static function sendToScreen(
        $screenId,
        $type,
        $payload = []
    ) {
        $screen = self::find($screenId);

        if ($screen) {
            $screen->pushCommand(
                $type,
                $payload
            );
        }
    }

    public static function sendToCluster(
        $clusterId,
        $type,
        $payload = []
    ) {
        $screens = self::whereHas(
            'clusters',
            function ($q) use ($clusterId) {
                $q->where(
                    'clusters.id',
                    $clusterId
                );
            }
        )->get();

        foreach ($screens as $screen) {
            $screen->pushCommand(
                $type,
                $payload
            );
        }
    }

    public static function broadcast(
        $type,
        $payload = []
    ) {
        foreach (self::all() as $screen) {
            $screen->pushCommand(
                $type,
                $payload
            );
        }
    }
}
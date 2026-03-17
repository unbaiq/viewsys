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
        'location',
        'orientation',
        'status',
        'content_version',
        'ip_address',
        'app_version',
        'device_model',
        'storage_free',
        'last_seen',
        'latitude',
        'longitude',
        'location_updated_at',

        // screenshot system
        'request_screenshot',
        'last_screenshot',
        'last_screenshot_at'
    ];

    protected $casts = [
        'status' => 'boolean',
        'request_screenshot' => 'boolean',
        'last_seen' => 'datetime',
        'last_screenshot_at' => 'datetime',
        'content_version' => 'integer'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
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
        return $this->belongsToMany(Cluster::class,'cluster_screen');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isOnline()
    {
        if (!$this->last_seen) {
            return false;
        }

        // consider device offline if no ping in last 2 minutes
        return $this->last_seen->gt(now()->subMinutes(2));
    }

    public function requestScreenshot()
    {
        $this->update([
            'request_screenshot' => true
        ]);
    }

    public function screenshotTaken($path)
    {
        $this->update([
            'request_screenshot' => false,
            'last_screenshot' => $path,
            'last_screenshot_at' => now()
        ]);
    }
}
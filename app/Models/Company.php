<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'email',
        'phone',
        'plan',
        'screen_limit',
        'storage_limit',
        'plan_start_date',
        'plan_end_date',
        'is_active',
    ];

    protected $casts = [
        'plan_start_date' => 'datetime',
        'plan_end_date'   => 'datetime',
        'is_active'       => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function storage()
    {
        return $this->hasOne(StorageUsage::class);
    }

    public function clusters()
    {
        return $this->hasMany(Cluster::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function remainingDays()
    {
        if (!$this->plan_end_date) {
            return null;
        }

        $days = now()->diffInDays($this->plan_end_date, false);

        return max($days, 0);
    }

    public function isExpired()
    {
        if (!$this->plan_end_date) {
            return false;
        }

        return now()->greaterThan($this->plan_end_date);
    }

    public function isActivePlan()
    {
        return !$this->isExpired() && $this->is_active;
    }
}
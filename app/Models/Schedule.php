<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $fillable = [
        'company_id',
        'screen_id',
        'playlist_id',
        'cluster_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'days_of_week',
        'priority',
        'is_default'
    ];

    protected $attributes = [
        'priority' => 1,
        'is_default' => false
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'days_of_week' => 'array',
        'priority'     => 'integer',
        'is_default'   => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    /*
    |--------------------------------------------------------------------------
    | DATETIME HELPERS
    |--------------------------------------------------------------------------
    */

    public function getStartDateTime()
    {
        if (!$this->start_date) return null;

        return Carbon::parse(
            $this->start_date->format('Y-m-d') . ' ' . ($this->start_time ?? '00:00:00')
        );
    }

    public function getEndDateTime()
    {
        if (!$this->end_date) return null;

        return Carbon::parse(
            $this->end_date->format('Y-m-d') . ' ' . ($this->end_time ?? '23:59:59')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE CHECK (FIXED)
    |--------------------------------------------------------------------------
    */

    public function isActive()
    {
        $now = Carbon::now();

        // ✅ FIX: full day name
        $day = strtolower($now->format('l')); // monday

        $start = $this->getStartDateTime();
        $end   = $this->getEndDateTime();

        if ($start && $now->lt($start)) return false;
        if ($end && $now->gt($end)) return false;

        if (!empty($this->days_of_week)) {
            $days = array_map('strtolower', $this->days_of_week);

            if (!in_array($day, $days)) return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE QUERY (OPTIMIZED)
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        $now = Carbon::now();
        $date = $now->toDateString();
        $time = $now->format('H:i:s');
        $day  = strtolower($now->format('l'));

        return $query
            ->where(function ($q) use ($date) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', $date);
            })
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $date);
            })
            ->where(function ($q) use ($time) {
                $q->whereNull('start_time')
                  ->orWhere('start_time', '<=', $time);
            })
            ->where(function ($q) use ($time) {
                $q->whereNull('end_time')
                  ->orWhere('end_time', '>=', $time);
            })
            ->where(function ($q) use ($day) {
                $q->whereNull('days_of_week')
                  ->orWhereJsonContains('days_of_week', $day);
            })
            ->orderByDesc('priority') // 🔥 IMPORTANT
            ->orderByDesc('is_default');
    }

    /*
    |--------------------------------------------------------------------------
    | MATCHING HELPERS (ADVANCED)
    |--------------------------------------------------------------------------
    */

    public function matchesScreen($screenId)
    {
        return $this->screen_id == $screenId;
    }

    public function matchesCluster($clusterId)
    {
        return $this->cluster_id == $clusterId;
    }

    public function isGlobal()
    {
        return !$this->screen_id && !$this->cluster_id;
    }
}
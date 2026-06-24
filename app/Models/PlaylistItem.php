<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PlaylistItem extends Model
{
    protected $fillable = [
        'company_id', // ✅ REQUIRED (SaaS)

        'playlist_id',
        'media_id',

        // 🎯 TARGETING
        'screen_id',
        'cluster_id',

        // 🎬 PLAY SETTINGS
        'order',
        'duration',

        // 📅 SCHEDULING
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'days_of_week'
    ];

    protected $casts = [
        'company_id'  => 'integer',

        'order'       => 'integer',
        'duration'    => 'integer',

        'screen_id'   => 'integer',
        'cluster_id'  => 'integer',

        'start_date'  => 'date',
        'end_date'    => 'date',

        'start_time'  => 'datetime:H:i:s',
        'end_time'    => 'datetime:H:i:s',

        'days_of_week'=> 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
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
            $this->start_date->toDateString() . ' ' . ($this->start_time ?? '00:00:00')
        );
    }

    public function getEndDateTime()
    {
        if (!$this->end_date) return null;

        return Carbon::parse(
            $this->end_date->toDateString() . ' ' . ($this->end_time ?? '23:59:59')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE CHECK
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        $now = now();

        if ($this->start_date && $now->lt($this->getStartDateTime())) {
            return false;
        }

        if ($this->end_date && $now->gt($this->getEndDateTime())) {
            return false;
        }

        if (!empty($this->days_of_week)) {
            $today = strtolower($now->format('l'));
            $days = array_map(fn($d) => strtolower($d), $this->days_of_week);

            if (!in_array($today, $days)) {
                return false;
            }
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | TARGET HELPERS
    |--------------------------------------------------------------------------
    */

    public function getTargetTypeAttribute(): string
    {
        if ($this->screen_id) return 'screen';
        if ($this->cluster_id) return 'cluster';
        return 'global';
    }

    public function isGlobal(): bool
    {
        return is_null($this->screen_id) && is_null($this->cluster_id);
    }

    /*
    |--------------------------------------------------------------------------
    | TARGET MATCH (SaaS SAFE 🔥)
    |--------------------------------------------------------------------------
    */

    public function matchesScreen(Screen $screen): bool
    {
        // 🚨 CRITICAL: company isolation
        if ($this->company_id !== $screen->company_id) {
            return false;
        }

        // 🎯 Screen-specific
        if ($this->screen_id && $this->screen_id == $screen->id) {
            return true;
        }

        // 🎯 Cluster-specific
        if ($this->cluster_id && $this->cluster_id == $screen->cluster_id) {
            return true;
        }

        // 🌍 Global
        return $this->isGlobal();
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL DURATION
    |--------------------------------------------------------------------------
    */

    public function getFinalDurationAttribute(): int
    {
        return $this->duration
            ?? $this->media?->final_duration
            ?? 10;
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL SCOPE (AUTO FILTER BY COMPANY 🔥🔥🔥)
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope('company', function ($query) {
            if (auth()->check()) {
                $query->where('company_id', auth()->user()->company_id);
            }
        });
    }
}
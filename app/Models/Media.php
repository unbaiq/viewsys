<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'file_name',
        'type',
        'file_path',
        'duration',          // video duration
        'display_duration',  // image display duration
        'size',
        'created_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS (IMPORTANT 🔥)
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'duration' => 'integer',
        'display_duration' => 'integer',
        'size' => 'integer',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_media')
            ->withPivot('order')
            ->withTimestamps();
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSORS 🚀
    |--------------------------------------------------------------------------
    */

    // Full file URL
    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    // Human readable size
    public function getSizeFormattedAttribute()
    {
        $bytes = $this->size;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    // Type label
    public function getTypeLabelAttribute()
    {
        return ucfirst($this->type);
    }

    // ✅ Get correct duration (smart)
    public function getFinalDurationAttribute()
    {
        return $this->isVideo()
            ? $this->duration
            : $this->display_duration;
    }

    // ⏱️ Human readable duration
    public function getDurationFormattedAttribute()
    {
        $seconds = $this->final_duration;

        if (!$seconds) return null;

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }


    /*
    |--------------------------------------------------------------------------
    | AUTO DELETE FILE
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::deleting(function ($media) {
            if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
        });
    }

    public function clusterLayouts()
{
    return $this->belongsToMany(
        ClusterLayout::class,
        'cluster_layout_media'
    )
    ->withPivot('sort_order')
    ->withTimestamps();
}
}
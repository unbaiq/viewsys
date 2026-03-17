<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistItem extends Model
{
    protected $fillable = [
        'playlist_id',
        'media_id',
        'order',
        'duration'
    ];

    protected $casts = [
        'order' => 'integer',
        'duration' => 'integer'
    ];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'file_path',
        'duration',
        'size',
        'created_by'
    ];

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

}
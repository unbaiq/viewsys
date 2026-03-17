<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'created_by'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function items()
    {
        return $this->hasMany(PlaylistItem::class);
    }
}
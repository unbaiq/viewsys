<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClusterLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'cluster_id',
        'zone_name',
        'sort_order'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function media()
    {
        return $this->belongsToMany(
            Media::class,
            'cluster_layout_media'
        )
        ->withPivot('sort_order')
        ->withTimestamps()
        ->orderBy('pivot_sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getMediaCountAttribute()
    {
        return $this->media()->count();
    }
}
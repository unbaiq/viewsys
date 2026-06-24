<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClusterLayoutMedia extends Model
{
    protected $table = 'cluster_layout_media';

    protected $fillable = [
        'cluster_layout_id',
        'media_id',
        'sort_order'
    ];

    public function layout()
    {
        return $this->belongsTo(
            ClusterLayout::class,
            'cluster_layout_id'
        );
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{

    protected $fillable = [
        'screen_id',
        'status',
        'last_ping',
        'ip_address',
        'app_version'
    ];

    protected $casts = [
        'last_ping'=>'datetime'
    ];

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }
    

}
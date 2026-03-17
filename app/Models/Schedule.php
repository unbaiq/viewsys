<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'company_id',
        'screen_id',
        'playlist_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'days_of_week',
        'priority'
    ];

    protected $attributes = [
        'priority' => 1
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_of_week' => 'array',
        'priority' => 'integer'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
    
}
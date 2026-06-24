<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

// RELATED MODELS
use App\Models\User;
use App\Models\Screen;
use App\Models\Media;
use App\Models\StorageUsage;
use App\Models\Cluster;
use App\Models\Playlist;
use App\Models\Schedule;
use App\Models\Notification;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        // Owner
        'user_id',

        // Basic
        'name',

        // Contact
        'email',
        'phone',
        'website',

        // Business
        'industry',
        'gst_number',
        'pan_number',

        // Address
        'address',
        'city',
        'state',
        'country',
        'zip_code',

        // Plan
        'plan',
        'screen_limit',
        'storage_limit',
        'user_limit',

        // Dates
        'plan_start_date',
        'plan_end_date',

        // Status
        'is_active',
        'is_trial',
    ];

    protected $casts = [
        'plan_start_date' => 'date',
        'plan_end_date'   => 'date',
        'is_active'       => 'boolean',
        'is_trial'        => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Company Users
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function storage()
    {
        return $this->hasOne(StorageUsage::class);
    }

    public function clusters()
    {
        return $this->hasMany(Cluster::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function remainingDays(): ?int
    {
        if (!$this->plan_end_date) {
            return null;
        }

        return now()->diffInDays($this->plan_end_date, false);
    }

    public function isExpired(): bool
    {
        return $this->plan_end_date
            ? now()->greaterThan($this->plan_end_date)
            : false;
    }

    public function isActivePlan(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    protected static function booted()
{
    static::updated(function ($company) {

        // Only trigger when status changes
        if ($company->isDirty('is_active')) {

            // Disable / Enable all users of this company
            $company->users()->update([
                'is_active' => $company->is_active
            ]);
        }

    });
}
}
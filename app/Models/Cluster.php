<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'location',
        'type',
        'header_text',
        'ticker_text',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function screens()
    {
        return $this->belongsToMany(
            Screen::class,
            'cluster_screen'
        )->withTimestamps();
    }

    /**
     * Layout Zones
     * Example:
     * left, right, sidebar, top_left, bottom_right etc
     */
    public function layouts()
    {
        return $this->hasMany(ClusterLayout::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCompany(Builder $query, $companyId = null)
    {
        $companyId = $companyId ?: Auth::user()?->company_id;

        if (!$companyId) {
            return $query;
        }

        return $query->where('company_id', $companyId);
    }

    /*
    |--------------------------------------------------------------------------
    | Layout Helpers
    |--------------------------------------------------------------------------
    */

    public function isFullscreenLayout()
    {
        return $this->type === 'fullscreen';
    }

    public function isHalfLayout()
    {
        return $this->type === 'half';
    }

    public function isSidebarLayout()
    {
        return $this->type === 'sidebar';
    }

    public function isHeaderLayout()
    {
        return $this->type === 'header';
    }

    public function isTickerLayout()
    {
        return $this->type === 'ticker';
    }

    public function isGridLayout()
    {
        return $this->type === 'grid';
    }

    public function isTripleLayout()
    {
        return $this->type === 'triple';
    }

    public function isMenuLayout()
    {
        return $this->type === 'menu';
    }

    /*
    |--------------------------------------------------------------------------
    | Zone Helpers
    |--------------------------------------------------------------------------
    */

    public function getZonesAttribute()
    {
        return match ($this->type) {

            'fullscreen' => [
                'main'
            ],

            'half' => [
                'left',
                'right'
            ],

            'sidebar' => [
                'main',
                'sidebar'
            ],

            'header' => [
                'header',
                'main'
            ],

            'ticker' => [
                'main',
                'ticker'
            ],

            'grid' => [
                'top_left',
                'top_right',
                'bottom_left',
                'bottom_right'
            ],

            'triple' => [
                'left',
                'center',
                'right'
            ],

            'menu' => [
                'header',
                'left',
                'center',
                'right'
            ],

            default => [
                'main'
            ]
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function activate()
    {
        return $this->update([
            'is_active' => true
        ]);
    }

    public function deactivate()
    {
        return $this->update([
            'is_active' => false
        ]);
    }
}
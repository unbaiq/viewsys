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
        'is_active' => 'boolean'
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
        return $this->belongsToMany(Screen::class, 'cluster_screen')
            ->withTimestamps();
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
        if (!$companyId) {
            $companyId = Auth::user()?->company_id;
        }

        if (!$companyId) {
            return $query;
        }

        return $query->where('company_id', $companyId);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

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


}
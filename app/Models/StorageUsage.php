<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageUsage extends Model
{
    use HasFactory;

    protected $table = 'storages';

    protected $fillable = [
        'company_id',
        'used',
        'storage_limit',
    ];

    protected $casts = [
        'used' => 'integer',
        'storage_limit' => 'integer',
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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function remaining()
    {
        return max($this->storage_limit - $this->used, 0);
    }

    public function percentUsed()
    {
        if ($this->storage_limit == 0) {
            return 0;
        }

        return round(($this->used / $this->storage_limit) * 100);
    }

    public function isWarning()
    {
        return $this->percentUsed() >= 80;
    }

    public function isCritical()
    {
        return $this->percentUsed() >= 95;
    }
}
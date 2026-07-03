<?php

namespace Modules\Tenant\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'status',
    ];

    public function settings(): HasOne
    {
        return $this->hasOne(TenantSetting::class);
    }
}

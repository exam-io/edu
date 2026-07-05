<?php

namespace Modules\FeatureManagement\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureCatalog extends Model
{
    use HasFactory;

    protected $table = 'feature_catalog';

    protected $fillable = [
        'key',
        'name',
        'description',
        'enabled_by_default',
    ];

    protected function casts(): array
    {
        return ['enabled_by_default' => 'bool'];
    }
}

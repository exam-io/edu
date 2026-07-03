<?php

namespace Modules\Media\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaAssetLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'media_asset_id',
        'link_type',
        'link_id',
    ];

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'media_asset_id');
    }
}

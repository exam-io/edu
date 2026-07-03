<?php

namespace Modules\Content\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'course_section_id',
        'media_asset_id',
        'title',
        'content_type',
        'content_body',
        'duration_seconds',
        'sort_order',
        'is_required',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'bool',
            'published_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(\Modules\Course\Domain\Models\Course::class, 'course_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(\Modules\Media\Domain\Models\MediaAsset::class, 'media_asset_id');
    }
}

<?php

namespace Modules\Content\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSection extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'title',
        'description',
        'sort_order',
        'status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(\Modules\Course\Domain\Models\Course::class, 'course_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ContentItem::class, 'course_section_id');
    }
}

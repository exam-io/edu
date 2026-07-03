<?php

namespace Modules\Course\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'created_by',
        'title',
        'code',
        'description',
        'level',
        'duration_minutes',
        'price',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'published_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(\Modules\Content\Domain\Models\CourseSection::class);
    }

    public function contentItems(): HasMany
    {
        return $this->hasMany(\Modules\Content\Domain\Models\ContentItem::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(\Modules\LMS\Domain\Models\CourseEnrollment::class);
    }
}

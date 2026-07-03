<?php

namespace Modules\LMS\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningProgress extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'lms_learning_progress';

    protected $fillable = [
        'tenant_id',
        'course_id',
        'student_id',
        'content_item_id',
        'progress_percent',
        'completed_items',
        'total_items',
        'last_activity_at',
        'completed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'last_activity_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(\Modules\Course\Domain\Models\Course::class, 'course_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\Modules\Student\Domain\Models\Student::class, 'student_id');
    }

    public function contentItem(): BelongsTo
    {
        return $this->belongsTo(\Modules\Content\Domain\Models\ContentItem::class, 'content_item_id');
    }
}

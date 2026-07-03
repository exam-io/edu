<?php

namespace Modules\LMS\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEnrollment extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'lms_course_enrollments';

    protected $fillable = [
        'tenant_id',
        'course_id',
        'student_id',
        'enrolled_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'enrolled_at' => 'date',
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
}

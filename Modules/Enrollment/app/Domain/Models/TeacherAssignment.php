<?php

namespace Modules\Enrollment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;
use Modules\Academic\Domain\Models\Section;
use Modules\Academic\Domain\Models\Subject;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Teacher\Domain\Models\Teacher;

class TeacherAssignment extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'teacher_id',
        'academic_session_id',
        'class_id',
        'section_id',
        'batch_id',
        'subject_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}

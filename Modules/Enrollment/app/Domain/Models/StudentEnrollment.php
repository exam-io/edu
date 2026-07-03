<?php

namespace Modules\Enrollment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;
use Modules\Academic\Domain\Models\Section;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Student\Domain\Models\Student;

class StudentEnrollment extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'academic_session_id',
        'class_id',
        'section_id',
        'batch_id',
        'enrollment_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
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
}

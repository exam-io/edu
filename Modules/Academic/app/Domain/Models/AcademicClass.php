<?php

namespace Modules\Academic\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Enrollment\Domain\Models\StudentEnrollment;
use Modules\Enrollment\Domain\Models\TeacherAssignment;
use Modules\Institute\Domain\Models\AcademicSession;

class AcademicClass extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'tenant_id',
        'program_id',
        'academic_session_id',
        'name',
        'code',
        'description',
        'status',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class, 'class_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class, 'class_id');
    }

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'class_id');
    }
}

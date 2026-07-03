<?php

namespace Modules\Academic\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Enrollment\Domain\Models\StudentEnrollment;
use Modules\Enrollment\Domain\Models\TeacherAssignment;

class Batch extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'class_id',
        'name',
        'start_date',
        'end_date',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class, 'batch_id');
    }

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'batch_id');
    }
}

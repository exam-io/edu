<?php

namespace Modules\Assessment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;
use Modules\Academic\Domain\Models\Program;
use Modules\Academic\Domain\Models\Section;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Shared\Domain\Models\TenantAwareModel;

class AssessmentAssignment extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'assessment_id',
        'academic_session_id',
        'program_id',
        'class_id',
        'section_id',
        'batch_id',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}

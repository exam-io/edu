<?php

namespace Modules\Assignment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Modules\Student\Domain\Models\Student;

class AssignmentSubmission extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'assessment_id',
        'student_id',
        'file_path',
        'submitted_at',
        'score',
        'feedback',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'score' => 'decimal:2',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}

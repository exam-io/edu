<?php

namespace Modules\Assessment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Modules\Student\Domain\Models\Student;

class AssessmentAttempt extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'assessment_id',
        'student_id',
        'started_at',
        'submitted_at',
        'time_taken',
        'score',
        'percentage',
        'rank',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'score' => 'decimal:2',
            'percentage' => 'decimal:2',
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

    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }
}

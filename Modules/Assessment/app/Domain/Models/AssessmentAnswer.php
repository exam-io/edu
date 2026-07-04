<?php

namespace Modules\Assessment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\QuestionBank\Domain\Models\Question;
use Modules\Shared\Domain\Models\TenantAwareModel;

class AssessmentAnswer extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'assessment_attempt_id',
        'question_id',
        'selected_answer',
        'is_correct',
        'marks_awarded',
    ];

    protected function casts(): array
    {
        return [
            'selected_answer' => 'array',
            'is_correct' => 'boolean',
            'marks_awarded' => 'decimal:2',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(AssessmentAttempt::class, 'assessment_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}

<?php

namespace Modules\Assessment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\QuestionBank\Domain\Models\Question;
use Modules\Shared\Domain\Models\TenantAwareModel;

class AssessmentQuestion extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'assessment_id',
        'question_id',
        'marks',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'marks' => 'decimal:2',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}

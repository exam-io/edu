<?php

namespace Modules\QuestionBank\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'question_set_id',
        'stem',
        'question_type',
        'difficulty',
        'options',
        'correct_answer',
        'explanation',
        'sort_order',
        'status',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'correct_answer' => 'array',
            'meta' => 'array',
        ];
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class, 'question_set_id');
    }
}

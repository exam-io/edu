<?php

namespace Modules\QuestionBank\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionSet extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'ai_generation_request_id',
        'content_source_id',
        'title',
        'description',
        'question_type',
        'difficulty',
        'total_questions',
        'status',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'question_set_id');
    }
}

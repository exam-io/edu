<?php

namespace Modules\Assessment\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Assignment\Domain\Models\AssignmentSubmission;
use Modules\QuestionBank\Domain\Models\Question;
use Modules\Shared\Domain\Models\TenantAwareModel;

class Assessment extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'type',
        'instructions',
        'start_at',
        'end_at',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'negative_marking',
        'randomize_questions',
        'randomize_options',
        'status',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'published_at' => 'datetime',
            'randomize_questions' => 'boolean',
            'randomize_options' => 'boolean',
            'total_marks' => 'decimal:2',
            'passing_marks' => 'decimal:2',
            'negative_marking' => 'decimal:2',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssessmentAssignment::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}

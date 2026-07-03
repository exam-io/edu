<?php

namespace Modules\AI\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AIGenerationRequest extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'ai_generation_requests';

    protected $fillable = [
        'tenant_id',
        'requested_by',
        'content_source_id',
        'generation_type',
        'status',
        'prompt_text',
        'options',
        'error_message',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'requested_by');
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(AIGenerationOutput::class, 'ai_generation_request_id');
    }
}

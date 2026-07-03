<?php

namespace Modules\AI\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIGenerationOutput extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'ai_generation_outputs';

    protected $fillable = [
        'tenant_id',
        'ai_generation_request_id',
        'output_type',
        'title',
        'body',
        'structured_payload',
        'model_name',
        'token_usage_input',
        'token_usage_output',
    ];

    protected function casts(): array
    {
        return [
            'structured_payload' => 'array',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(AIGenerationRequest::class, 'ai_generation_request_id');
    }
}

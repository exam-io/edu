<?php

namespace Modules\ContentProcessing\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class ContentExtraction extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'content_source_id',
        'status',
        'extracted_text',
        'word_count',
        'error_message',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }
}

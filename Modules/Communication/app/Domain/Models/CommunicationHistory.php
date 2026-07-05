<?php

namespace Modules\Communication\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class CommunicationHistory extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'communication_histories';

    protected $fillable = [
        'tenant_id',
        'source_type',
        'source_id',
        'user_id',
        'channel',
        'subject',
        'content',
        'status',
        'provider_response',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_response' => 'array',
            'sent_at' => 'datetime',
        ];
    }
}

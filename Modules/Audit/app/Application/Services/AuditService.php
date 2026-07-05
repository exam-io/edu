<?php

namespace Modules\Audit\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Audit\Application\Contracts\AuditServiceInterface;
use Modules\Audit\Application\DTOs\AuditQueryData;
use Modules\Audit\Application\DTOs\AuditRecordData;
use Modules\Audit\Domain\Events\AuditRecordCreated;
use Modules\Audit\Domain\Models\AuditLog;

class AuditService implements AuditServiceInterface
{
    public function logs(int $tenantId, AuditQueryData $query): LengthAwarePaginator
    {
        $builder = AuditLog::query()->where('tenant_id', $tenantId)->latest('occurred_at');

        if ($query->action !== null && $query->action !== '') {
            $builder->where('action', $query->action);
        }

        if ($query->actor !== null && $query->actor !== '') {
            $builder->where('actor_type', $query->actor);
        }

        return $builder->paginate($query->perPage);
    }

    public function record(int $tenantId, AuditRecordData $data): AuditLog
    {
        $log = AuditLog::query()->create([
            'tenant_id' => $tenantId,
            'actor_user_id' => $data->actorUserId,
            'actor_type' => $data->actorType,
            'action' => $data->action,
            'resource_type' => $data->resourceType,
            'resource_id' => $data->resourceId,
            'before_state' => $this->redact($data->beforeState),
            'after_state' => $this->redact($data->afterState),
            'context' => $this->redact($data->context),
            'occurred_at' => now(),
        ]);

        event(new AuditRecordCreated($tenantId, $log->id));

        return $log;
    }

    private function redact(array $payload): array
    {
        $sensitive = ['password', 'token', 'secret', 'api_key', 'authorization'];

        foreach ($payload as $key => $value) {
            if (in_array(strtolower((string) $key), $sensitive, true)) {
                $payload[$key] = '[REDACTED]';
                continue;
            }

            if (is_array($value)) {
                $payload[$key] = $this->redact($value);
            }
        }

        return $payload;
    }
}

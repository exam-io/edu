<?php

namespace Modules\System\Application\Services;

use Modules\System\Application\Contracts\SystemSecurityServiceInterface;
use Modules\System\Application\DTOs\SecurityPolicyData;
use Modules\System\Domain\Events\SecurityPolicyChanged;
use Modules\System\Domain\Models\SystemSecurityPolicy;

class SystemSecurityService implements SystemSecurityServiceInterface
{
    public function current(int $tenantId): ?SystemSecurityPolicy
    {
        return SystemSecurityPolicy::query()->where('tenant_id', $tenantId)->latest('id')->first();
    }

    public function update(int $tenantId, SecurityPolicyData $data): SystemSecurityPolicy
    {
        $policy = SystemSecurityPolicy::query()->firstOrNew(['tenant_id' => $tenantId]);
        $policy->fill([
            'force_mfa' => $data->forceMfa,
            'session_ttl_minutes' => $data->sessionTtlMinutes,
            'password_rotation_days' => $data->passwordRotationDays,
            'allow_ip_restriction' => $data->allowIpRestriction,
            'allowed_ip_ranges' => $data->allowedIpRanges,
            'strict_transport_security' => $data->strictTransportSecurity,
            'meta' => [],
        ]);
        $policy->save();

        event(new SecurityPolicyChanged($tenantId, $policy->id));

        return $policy->refresh();
    }
}

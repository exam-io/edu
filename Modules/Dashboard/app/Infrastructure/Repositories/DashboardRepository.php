<?php

namespace Modules\Dashboard\Infrastructure\Repositories;

use Modules\Dashboard\Application\Contracts\DashboardRepositoryInterface;
use Modules\Dashboard\Domain\Models\DashboardDefinition;
use Modules\Dashboard\Domain\Models\UserDashboardPreference;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function findByRole(int $tenantId, string $roleKey): ?DashboardDefinition
    {
        return DashboardDefinition::query()
            ->where('tenant_id', $tenantId)
            ->where('role_key', $roleKey)
            ->with('widgets')
            ->first();
    }

    public function createDefault(int $tenantId, string $roleKey): DashboardDefinition
    {
        return DashboardDefinition::query()->create([
            'tenant_id' => $tenantId,
            'name' => ucfirst($roleKey) . ' Dashboard',
            'role_key' => $roleKey,
            'is_default' => true,
            'layout' => [
                'version' => 1,
                'grid' => '12',
            ],
        ]);
    }

    public function preferenceForUser(int $tenantId, int $userId): ?UserDashboardPreference
    {
        return UserDashboardPreference::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->first();
    }

    public function upsertPreference(int $tenantId, int $userId, array $attributes): UserDashboardPreference
    {
        return UserDashboardPreference::query()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'user_id' => $userId,
            ],
            $attributes,
        );
    }
}

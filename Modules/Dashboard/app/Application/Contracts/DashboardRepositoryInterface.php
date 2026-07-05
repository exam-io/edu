<?php

namespace Modules\Dashboard\Application\Contracts;

use Modules\Dashboard\Domain\Models\DashboardDefinition;
use Modules\Dashboard\Domain\Models\UserDashboardPreference;

interface DashboardRepositoryInterface
{
    public function findByRole(int $tenantId, string $roleKey): ?DashboardDefinition;

    public function createDefault(int $tenantId, string $roleKey): DashboardDefinition;

    public function preferenceForUser(int $tenantId, int $userId): ?UserDashboardPreference;

    public function upsertPreference(int $tenantId, int $userId, array $attributes): UserDashboardPreference;
}

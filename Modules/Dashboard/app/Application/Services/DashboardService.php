<?php

namespace Modules\Dashboard\Application\Services;

use Illuminate\Support\Facades\Event;
use Modules\Dashboard\Application\Contracts\DashboardRepositoryInterface;
use Modules\Dashboard\Application\Contracts\DashboardServiceInterface;
use Modules\Dashboard\Application\DTOs\DashboardPreferenceData;
use Modules\Dashboard\Domain\Events\DashboardConfigured;
use Modules\Dashboard\Domain\Events\DashboardViewed;
use Modules\Dashboard\Domain\Models\DashboardDefinition;
use Modules\Dashboard\Domain\Models\UserDashboardPreference;
use Modules\Tenant\Application\Services\TenantContextService;

class DashboardService implements DashboardServiceInterface
{
    public function __construct(
        private readonly DashboardRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function myDashboard(): DashboardDefinition
    {
        $tenantId = $this->tenantContext->requiredTenantId();
        $role = (string) (auth()->user()?->getRoleNames()->first() ?? 'general');

        $dashboard = $this->repository->findByRole($tenantId, $role);

        if (! $dashboard instanceof DashboardDefinition) {
            $dashboard = $this->repository->createDefault($tenantId, $role);
        }

        Event::dispatch(new DashboardViewed($dashboard->id, $tenantId, $role));

        return $dashboard->load('widgets');
    }

    public function updatePreference(DashboardPreferenceData $data): UserDashboardPreference
    {
        $tenantId = $this->tenantContext->requiredTenantId();
        $userId = (int) auth()->id();

        $preference = $this->repository->upsertPreference($tenantId, $userId, [
            'dashboard_definition_id' => $data->dashboardDefinitionId,
            'preferences' => $data->preferences,
        ]);

        Event::dispatch(new DashboardConfigured($tenantId, $userId, $preference->id));

        return $preference;
    }
}

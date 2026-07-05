<?php

namespace Modules\Dashboard\Application\Contracts;

use Modules\Dashboard\Application\DTOs\DashboardPreferenceData;
use Modules\Dashboard\Domain\Models\DashboardDefinition;
use Modules\Dashboard\Domain\Models\UserDashboardPreference;

interface DashboardServiceInterface
{
    public function myDashboard(): DashboardDefinition;

    public function updatePreference(DashboardPreferenceData $data): UserDashboardPreference;
}

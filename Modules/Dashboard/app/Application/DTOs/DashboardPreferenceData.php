<?php

namespace Modules\Dashboard\Application\DTOs;

readonly class DashboardPreferenceData
{
    public function __construct(
        public ?int $dashboardDefinitionId,
        public array $preferences,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            dashboardDefinitionId: isset($input['dashboard_definition_id']) ? (int) $input['dashboard_definition_id'] : null,
            preferences: isset($input['preferences']) && is_array($input['preferences']) ? $input['preferences'] : [],
        );
    }
}

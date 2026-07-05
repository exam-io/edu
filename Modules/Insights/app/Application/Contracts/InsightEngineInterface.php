<?php

namespace Modules\Insights\Application\Contracts;

interface InsightEngineInterface
{
    public function generateForTenant(int $tenantId): int;
}

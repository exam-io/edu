<?php

namespace Modules\Reporting\Application\Contracts;

interface ReportEngineInterface
{
    public function generate(int $tenantId, int $templateId, array $filters): array;
}

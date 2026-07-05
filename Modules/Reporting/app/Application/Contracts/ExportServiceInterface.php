<?php

namespace Modules\Reporting\Application\Contracts;

interface ExportServiceInterface
{
    public function export(int $tenantId, int $executionId, array $rows, string $format = 'csv'): string;
}

<?php

namespace Modules\Insights\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Insights\Application\DTOs\InsightQueryData;

interface InsightsServiceInterface
{
    public function listGenerated(InsightQueryData $query): LengthAwarePaginator;

    public function generateNow(): int;
}

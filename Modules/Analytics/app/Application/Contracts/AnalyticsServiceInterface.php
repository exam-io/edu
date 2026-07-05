<?php

namespace Modules\Analytics\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Analytics\Application\DTOs\AnalyticsQueryData;
use Modules\Analytics\Application\DTOs\TrackEventData;
use Modules\Analytics\Domain\Models\AnalyticsEvent;

interface AnalyticsServiceInterface
{
    public function listEvents(AnalyticsQueryData $query): LengthAwarePaginator;

    public function track(TrackEventData $data): AnalyticsEvent;

    public function metricSeries(string $metricKey): Collection;
}

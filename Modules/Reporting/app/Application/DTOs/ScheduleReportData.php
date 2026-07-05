<?php

namespace Modules\Reporting\Application\DTOs;

readonly class ScheduleReportData
{
    public function __construct(
        public int $reportTemplateId,
        public string $frequency,
        public string $nextRunAt,
        public array $filters,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            reportTemplateId: (int) $input['report_template_id'],
            frequency: (string) $input['frequency'],
            nextRunAt: (string) $input['next_run_at'],
            filters: is_array($input['filters'] ?? null) ? $input['filters'] : [],
        );
    }
}

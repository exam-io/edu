<?php

namespace Modules\Reporting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'report_template_id' => $this->report_template_id,
            'frequency' => $this->frequency,
            'next_run_at' => $this->next_run_at?->toIso8601String(),
            'is_active' => (bool) $this->is_active,
            'filters' => $this->filters,
        ];
    }
}

<?php

namespace Modules\Insights\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneratedInsightResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'insight_rule_id' => $this->insight_rule_id,
            'title' => $this->title,
            'summary' => $this->summary,
            'severity' => $this->severity,
            'context_payload' => $this->context_payload,
            'generated_at' => $this->generated_at?->toIso8601String(),
        ];
    }
}

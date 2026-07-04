<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Assessment\Domain\Models\AssessmentAttempt;

class GenerateResultAnalytics implements ShouldQueue
{
    public function handle(object $event): void
    {
        if (! property_exists($event, 'tenantId') || ! property_exists($event, 'assessmentId')) {
            return;
        }

        $tenantId = is_int($event->tenantId) ? $event->tenantId : null;
        $assessmentId = is_int($event->assessmentId) ? $event->assessmentId : null;

        if ($tenantId === null || $assessmentId === null) {
            return;
        }

        $assessment = Assessment::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($assessmentId)
            ->first();

        if (! $assessment instanceof Assessment) {
            return;
        }

        $attemptQuery = AssessmentAttempt::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_id', $assessmentId)
            ->whereIn('status', ['submitted', 'evaluated']);

        $total = (clone $attemptQuery)->count();
        $avgScore = (float) ((clone $attemptQuery)->avg('score') ?? 0);
        $avgPercentage = (float) ((clone $attemptQuery)->avg('percentage') ?? 0);
        $passCount = (clone $attemptQuery)->where('score', '>=', (float) $assessment->passing_marks)->count();

        $payload = [
            'tenant_id' => $tenantId,
            'assessment_id' => $assessmentId,
            'total_submissions' => $total,
            'avg_score' => round($avgScore, 2),
            'avg_percentage' => round($avgPercentage, 2),
            'pass_rate' => $total > 0 ? round(($passCount / $total) * 100, 2) : 0.0,
            'updated_at' => now()->toIso8601String(),
        ];

        Cache::put("assessment:analytics:tenant:{$tenantId}:assessment:{$assessmentId}", $payload, now()->addHours(6));
    }
}

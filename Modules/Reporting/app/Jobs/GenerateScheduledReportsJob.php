<?php

namespace Modules\Reporting\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Reporting\Application\Contracts\ReportRepositoryInterface;

class GenerateScheduledReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ReportRepositoryInterface $repository): void
    {
        $schedules = $repository->dueSchedules();

        foreach ($schedules as $schedule) {
            $execution = $repository->createExecution([
                'tenant_id' => $schedule->tenant_id,
                'report_template_id' => $schedule->report_template_id,
                'status' => 'queued',
                'filters' => is_array($schedule->filters) ? $schedule->filters : [],
                'requested_by' => $schedule->created_by,
            ]);

            GenerateReportJob::dispatch($schedule->tenant_id, $execution->id);

            $repository->updateSchedule($schedule, [
                'next_run_at' => $this->nextRunAt((string) $schedule->frequency),
            ]);
        }
    }

    private function nextRunAt(string $frequency): string
    {
        return match ($frequency) {
            'hourly' => now()->addHour()->toDateTimeString(),
            'weekly' => now()->addWeek()->toDateTimeString(),
            default => now()->addDay()->toDateTimeString(),
        };
    }
}

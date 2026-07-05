<?php

namespace Modules\Audit\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Modules\Audit\Domain\Models\AuditLog;

class ExportAuditLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $tenantId) {}

    public function handle(): void
    {
        $rows = AuditLog::query()
            ->where('tenant_id', $this->tenantId)
            ->latest('occurred_at')
            ->limit(100)
            ->get()
            ->map(fn (AuditLog $row) => [
                'id' => $row->id,
                'action' => $row->action,
                'resource_type' => $row->resource_type,
                'resource_id' => $row->resource_id,
                'occurred_at' => $row->occurred_at?->toIso8601String(),
            ])
            ->values()
            ->all();

        Storage::disk(config('filesystems.default'))->put(
            'audit/tenant-' . $this->tenantId . '/latest.json',
            json_encode($rows, JSON_PRETTY_PRINT),
        );
    }
}

<?php

namespace Modules\Operations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Monitoring\Domain\Models\AlertIncident;
use Modules\Operations\Domain\Models\QueueOperationLog;

class PruneOperationalDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $cutoff = now()->subDays((int) config('operations.retention_days', 90));

        AlertIncident::query()->where('created_at', '<', $cutoff)->delete();
        QueueOperationLog::query()->where('created_at', '<', $cutoff)->delete();
    }
}

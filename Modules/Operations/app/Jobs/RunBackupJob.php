<?php

namespace Modules\Operations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Operations\Application\Contracts\BackupServiceInterface;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $tenantId,
        private readonly array $options = [],
    ) {}

    public function handle(BackupServiceInterface $service): void
    {
        $service->trigger($this->tenantId, $this->options);
    }
}

<?php

namespace Modules\System\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\System\Application\Contracts\SystemHealthServiceInterface;

class RunSystemHealthChecksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $tenantId) {}

    public function handle(SystemHealthServiceInterface $service): void
    {
        $service->runChecks($this->tenantId);
    }
}

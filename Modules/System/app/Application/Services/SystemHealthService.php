<?php

namespace Modules\System\Application\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\System\Application\Contracts\SystemHealthServiceInterface;
use Modules\System\Domain\Events\SystemHealthCheckFailed;
use Modules\System\Domain\Models\SystemHealthCheck;

class SystemHealthService implements SystemHealthServiceInterface
{
    public function latest(int $tenantId): array
    {
        return SystemHealthCheck::query()
            ->where('tenant_id', $tenantId)
            ->latest('checked_at')
            ->get()
            ->groupBy('check_name')
            ->map(fn ($group) => $group->first())
            ->values()
            ->all();
    }

    public function runChecks(int $tenantId): array
    {
        $results = [];

        $dbOk = $this->checkDatabase();
        $results[] = $this->persistResult($tenantId, 'database', $dbOk ? 'ok' : 'failed');

        $cacheOk = $this->checkCache();
        $results[] = $this->persistResult($tenantId, 'cache', $cacheOk ? 'ok' : 'failed');

        $queueDriver = (string) config('queue.default', 'sync');
        $queueOk = $queueDriver !== '';
        $results[] = $this->persistResult($tenantId, 'queue', $queueOk ? 'ok' : 'failed', ['driver' => $queueDriver]);

        foreach ($results as $result) {
            if ($result->status === 'failed') {
                event(new SystemHealthCheckFailed($tenantId, $result->check_name, $result->meta ?? []));
            }
        }

        return $results;
    }

    public function persistResult(int $tenantId, string $check, string $status, array $meta = []): SystemHealthCheck
    {
        return SystemHealthCheck::query()->create([
            'tenant_id' => $tenantId,
            'check_name' => $check,
            'status' => $status,
            'checked_at' => now(),
            'meta' => $meta,
        ]);
    }

    private function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function checkCache(): bool
    {
        try {
            $key = 'system:health:' . uniqid('', true);
            Cache::put($key, 'ok', 30);
            return Cache::get($key) === 'ok';
        } catch (\Throwable) {
            return false;
        }
    }
}

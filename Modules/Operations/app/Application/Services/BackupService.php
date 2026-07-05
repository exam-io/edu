<?php

namespace Modules\Operations\Application\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Operations\Application\Contracts\BackupServiceInterface;
use Modules\Operations\Domain\Events\BackupCompleted;
use Modules\Operations\Domain\Models\BackupExecution;

class BackupService implements BackupServiceInterface
{
    public function trigger(int $tenantId, array $options = []): BackupExecution
    {
        $disk = (string) ($options['disk'] ?? config('operations.default_backup_disk', config('filesystems.default')));
        $path = 'ops/backups/tenant-' . $tenantId . '/' . now()->format('Ymd_His') . '.json';

        $execution = BackupExecution::query()->create([
            'tenant_id' => $tenantId,
            'status' => 'running',
            'started_at' => now(),
            'completed_at' => null,
            'storage_disk' => $disk,
            'storage_path' => $path,
            'meta' => ['type' => 'logical'],
        ]);

        Storage::disk($disk)->put($path, json_encode([
            'tenant_id' => $tenantId,
            'generated_at' => now()->toIso8601String(),
            'note' => 'Logical backup placeholder for enterprise backup pipeline.',
        ], JSON_PRETTY_PRINT));

        $execution->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        event(new BackupCompleted($tenantId, $execution->id));

        return $execution->refresh();
    }

    public function latest(int $tenantId): ?BackupExecution
    {
        return BackupExecution::query()->where('tenant_id', $tenantId)->latest('id')->first();
    }
}

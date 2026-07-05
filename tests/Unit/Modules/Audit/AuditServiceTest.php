<?php

namespace Tests\Unit\Modules\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Audit\Application\DTOs\AuditRecordData;
use Modules\Audit\Application\Services\AuditService;
use Modules\Audit\Domain\Models\AuditLog;
use Modules\Tenant\Domain\Models\Tenant;
use Tests\TestCase;

class AuditServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_record_redacts_sensitive_nested_fields(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Audit Unit Tenant',
            'slug' => 'audit-unit',
            'domain' => 'audit-unit.localhost',
            'status' => 'active',
        ]);

        $service = new AuditService();
        $service->record($tenant->id, new AuditRecordData(
            actorUserId: null,
            actorType: 'system',
            action: 'credentials.rotate',
            resourceType: 'security_policy',
            resourceId: 'policy-1',
            beforeState: ['password' => 'old', 'nested' => ['token' => 'tok-old']],
            afterState: ['password' => 'new', 'nested' => ['token' => 'tok-new']],
            context: ['authorization' => 'Bearer abc', 'safe' => 'ok'],
        ));

        $log = AuditLog::query()->firstOrFail();

        $this->assertSame('[REDACTED]', $log->before_state['password']);
        $this->assertSame('[REDACTED]', $log->before_state['nested']['token']);
        $this->assertSame('[REDACTED]', $log->after_state['password']);
        $this->assertSame('[REDACTED]', $log->context['authorization']);
        $this->assertSame('ok', $log->context['safe']);
    }
}

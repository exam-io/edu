<?php

namespace Tests\Feature\Modules\Reporting;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ReportingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_reporting_templates_run_and_schedule_endpoints(): void
    {
        $tenant = $this->createTenant('reporting-a');
        $user = $this->createUser($tenant, 'reporting-user@test.local');

        foreach (['reporting.view', 'reporting.create', 'reporting.run', 'reporting.schedule'] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo(['reporting.view', 'reporting.create', 'reporting.run', 'reporting.schedule']);

        Sanctum::actingAs($user);

        $create = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/reports/templates', [
                'name' => 'Snapshot Report',
                'slug' => 'snapshot-report',
                'source' => 'analytics_metric_snapshots',
                'definition' => ['metric_key' => 'events.count'],
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $templateId = (int) $create->json('data.id');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/reports/templates/' . $templateId . '/run', [
                'filters' => ['metric_key' => 'events.count'],
            ])
            ->assertStatus(202)
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/reports/schedules', [
                'report_template_id' => $templateId,
                'frequency' => 'daily',
                'next_run_at' => now()->addHour()->toIso8601String(),
                'filters' => ['metric_key' => 'events.count'],
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);
    }

    private function createTenant(string $slug): Tenant
    {
        return Tenant::query()->create([
            'name' => ucfirst($slug) . ' Institute',
            'slug' => $slug,
            'domain' => $slug . '.localhost',
            'status' => 'active',
        ]);
    }

    private function createUser(Tenant $tenant, string $email): User
    {
        return User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Reporting API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

<?php

namespace Tests\Feature\Modules\Insights;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Analytics\Domain\Models\MetricSnapshot;
use Modules\Insights\Domain\Models\InsightRule;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class InsightsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_insight_generation_and_listing_endpoints(): void
    {
        $tenant = $this->createTenant('insights-a');
        $user = $this->createUser($tenant, 'insights-user@test.local');

        Permission::findOrCreate('insights.view', 'web');
        Permission::findOrCreate('insights.generate', 'web');
        $user->givePermissionTo(['insights.view', 'insights.generate']);

        InsightRule::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'High Event Count',
            'metric_key' => 'events.count',
            'operator' => '>=',
            'threshold' => 10,
            'severity' => 'high',
            'is_active' => true,
        ]);

        MetricSnapshot::query()->create([
            'tenant_id' => $tenant->id,
            'metric_key' => 'events.count',
            'dimension_key' => 'event_name',
            'dimension_value' => 'assessment.submitted',
            'metric_value' => 12,
            'period_start' => now()->subHour(),
            'period_end' => now(),
            'generated_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/insights/generate')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/insights')
            ->assertOk()
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
            'name' => 'Insights API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

<?php

namespace Tests\Feature\Modules\Payment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PaymentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_intent_capture_transaction_and_webhook_work(): void
    {
        $tenant = $this->createTenant('pay-a');
        $user = $this->createUser($tenant, 'pay-a@test.local');

        foreach (['payment.transaction.view', 'payment.intent.manage', 'payment.webhook.manage'] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo(['payment.transaction.view', 'payment.intent.manage', 'payment.webhook.manage']);

        Sanctum::actingAs($user);

        $intentResponse = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/payment/intents', [
                'amount' => 49.99,
                'currency' => 'USD',
                'provider' => 'null',
            ])
            ->assertStatus(201);

        $intentId = (int) $intentResponse->json('data.id');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/payment/intents/' . $intentId . '/capture', [
                'amount' => 49.99,
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'succeeded');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/v1/payment/transactions')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/v1/payment/webhooks/null', [
                'event' => 'payment.succeeded',
                'provider_intent_id' => 'demo-intent',
            ])
            ->assertStatus(202)
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
            'name' => 'Payment User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

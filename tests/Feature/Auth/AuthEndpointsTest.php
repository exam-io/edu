<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_resolved_tenant_context(): void
    {
        $tenant = $this->createTenant('localhost', 'alpha');

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Auth User',
            'email' => 'auth.user@alpha.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'language' => 'hi',
            'theme' => 'dark',
            'timezone' => 'Asia/Kolkata',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'auth.user@alpha.test')
            ->assertJsonPath('data.user.tenant_id', $tenant->id)
            ->assertJsonPath('data.user.settings.language', 'hi')
            ->assertJsonPath('data.user.settings.theme', 'dark');
    }

    public function test_user_can_login_fetch_context_and_logout(): void
    {
        $tenant = $this->createTenant('localhost', 'beta');
        $this->createDefaultRoles();

        $user = User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Beta Admin',
            'email' => 'admin@beta.test',
            'password' => 'password123',
            'status' => 'active',
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        $user->assignRole('Super Admin');

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'admin@beta.test',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('data.token');

        $loginResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'admin@beta.test');

        $contextResponse = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/auth/context');

        $contextResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'admin@beta.test')
            ->assertJsonPath('data.tenant.id', $tenant->id);

        $logoutResponse = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/auth/logout');

        $logoutResponse
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_me_requires_verified_email(): void
    {
        $tenant = $this->createTenant('localhost', 'gamma');

        $user = User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Gamma User',
            'email' => 'user@gamma.test',
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => null,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/auth/me')
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }

    public function test_login_is_rate_limited_after_multiple_attempts(): void
    {
        $this->createTenant('localhost', 'delta');

        for ($attempt = 1; $attempt <= 6; $attempt++) {
            $this->postJson('/api/auth/login', [
                'email' => 'unknown@delta.test',
                'password' => 'wrong-pass-123',
            ]);
        }

        $this->postJson('/api/auth/login', [
            'email' => 'unknown@delta.test',
            'password' => 'wrong-pass-123',
        ])
            ->assertStatus(429);
    }

    private function createTenant(string $domain, string $slug): Tenant
    {
        return Tenant::query()->create([
            'name' => ucfirst($slug).' Institute',
            'slug' => $slug,
            'domain' => $domain,
            'status' => 'active',
        ]);
    }

    private function createDefaultRoles(): void
    {
        foreach (['Super Admin', 'Institute Admin', 'Teacher', 'Student', 'Parent'] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }
}

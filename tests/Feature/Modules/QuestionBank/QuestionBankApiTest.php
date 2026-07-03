<?php

namespace Tests\Feature\Modules\QuestionBank;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class QuestionBankApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_question_set_endpoints_require_permissions(): void
    {
        $tenant = $this->createTenant('qb-a');
        $userWithoutPermissions = $this->createUser($tenant, 'qb-user-no-perm@a.test');

        Sanctum::actingAs($userWithoutPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/question-sets')
            ->assertForbidden();

        $userWithPermissions = $this->createUser($tenant, 'qb-user-with-perm@a.test');
        Permission::findOrCreate('question.bank.view', 'web');
        Permission::findOrCreate('question.bank.create', 'web');
        $userWithPermissions->givePermissionTo(['question.bank.view', 'question.bank.create']);

        Sanctum::actingAs($userWithPermissions);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/question-sets', [
                'title' => 'Algebra Basics',
                'question_type' => 'mcq',
                'difficulty' => 'easy',
                'questions' => [
                    [
                        'stem' => 'What is 2 + 2?',
                        'question_type' => 'mcq',
                        'correct_answer' => ['value' => '4'],
                    ],
                ],
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Algebra Basics');

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/question-sets?per_page=5')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.per_page', 5);
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
            'name' => 'Question Bank API User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

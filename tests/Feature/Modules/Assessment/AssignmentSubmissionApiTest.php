<?php

namespace Tests\Feature\Modules\Assessment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Assignment\Domain\Models\AssignmentSubmission;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AssignmentSubmissionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_assignment(): void
    {
        $tenant = $this->createTenant('assign-a');
        [$user, $student] = $this->createStudentUser($tenant, 'student-assignment@test.local');

        Permission::findOrCreate('assignment.submit', 'web');
        $user->givePermissionTo(['assignment.submit']);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Essay Homework',
            'type' => 'Assignment',
            'total_marks' => 20,
            'passing_marks' => 10,
            'status' => 'published',
            'created_by' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assignments/' . $assessment->id . '/submit', [
                'file_path' => 'tenants/' . $tenant->id . '/assignments/demo.pdf',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.assessment_id', $assessment->id);
    }

    public function test_submission_list_supports_filters_and_search(): void
    {
        $tenant = $this->createTenant('assign-filters');
        [$user, $student] = $this->createStudentUser($tenant, 'submission-viewer@test.local');
        [, $studentTwo] = $this->createStudentUser($tenant, 'submission-viewer-two@test.local');

        Permission::findOrCreate('submission.view', 'web');
        $user->givePermissionTo(['submission.view']);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Assignment Filter Test',
            'type' => 'Assignment',
            'total_marks' => 20,
            'passing_marks' => 10,
            'status' => 'published',
            'created_by' => $user->id,
        ]);

        AssignmentSubmission::query()->create([
            'tenant_id' => $tenant->id,
            'assessment_id' => $assessment->id,
            'student_id' => $student->id,
            'file_path' => 'tenants/' . $tenant->id . '/assignments/essay-final.pdf',
            'status' => 'graded',
            'submitted_at' => now(),
        ]);

        AssignmentSubmission::query()->create([
            'tenant_id' => $tenant->id,
            'assessment_id' => $assessment->id,
            'student_id' => $studentTwo->id,
            'file_path' => 'tenants/' . $tenant->id . '/assignments/lab-notes.pdf',
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/assignments/submissions?status=graded&q=essay')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.status', 'graded');
    }

    public function test_student_cannot_submit_duplicate_assignment_submission(): void
    {
        $tenant = $this->createTenant('assign-duplicate');
        [$user, $student] = $this->createStudentUser($tenant, 'student-duplicate@test.local');

        Permission::findOrCreate('assignment.submit', 'web');
        $user->givePermissionTo(['assignment.submit']);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Duplicate Guard Assignment',
            'type' => 'Assignment',
            'total_marks' => 20,
            'passing_marks' => 10,
            'status' => 'published',
            'created_by' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assignments/' . $assessment->id . '/submit', [
                'file_path' => 'tenants/' . $tenant->id . '/assignments/first.pdf',
            ])
            ->assertCreated();

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assignments/' . $assessment->id . '/submit', [
                'file_path' => 'tenants/' . $tenant->id . '/assignments/second.pdf',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['assignment']);
    }

    public function test_student_cannot_submit_non_assignment_or_unpublished_assessment(): void
    {
        $tenant = $this->createTenant('assign-type');
        [$user, $student] = $this->createStudentUser($tenant, 'student-type@test.local');

        Permission::findOrCreate('assignment.submit', 'web');
        $user->givePermissionTo(['assignment.submit']);

        $quiz = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Quiz Not Assignment',
            'type' => 'Quiz',
            'total_marks' => 20,
            'passing_marks' => 10,
            'status' => 'published',
            'created_by' => $user->id,
        ]);

        $draftAssignment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Draft Assignment',
            'type' => 'Assignment',
            'total_marks' => 20,
            'passing_marks' => 10,
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assignments/' . $quiz->id . '/submit', [
                'file_path' => 'tenants/' . $tenant->id . '/assignments/quiz.pdf',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['assessment_id']);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assignments/' . $draftAssignment->id . '/submit', [
                'file_path' => 'tenants/' . $tenant->id . '/assignments/draft.pdf',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['assessment_id']);
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

    private function createStudentUser(Tenant $tenant, string $email): array
    {
        $unique = substr(md5($email), 0, 8);

        $user = User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Student User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $student = Student::query()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'admission_no' => 'ADM-' . $tenant->id . '-' . $unique,
            'roll_no' => 'R-' . $tenant->id . '-' . $unique,
            'first_name' => 'Test',
            'last_name' => 'Student',
            'gender' => 'other',
            'date_of_birth' => '2010-01-01',
            'admission_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        return [$user, $student];
    }
}

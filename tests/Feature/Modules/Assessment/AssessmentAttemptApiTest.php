<?php

namespace Tests\Feature\Modules\Assessment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\QuestionBank\Domain\Models\QuestionSet;
use Modules\QuestionBank\Domain\Models\Question;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AssessmentAttemptApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_create_duplicate_active_attempt_for_same_assessment(): void
    {
        $tenant = $this->createTenant('attempt-a');
        [$user, $student] = $this->createStudentUser($tenant, 'student-attempt@test.local');

        Permission::findOrCreate('assessment_attempt.create', 'web');
        $user->givePermissionTo(['assessment_attempt.create']);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Math Quiz',
            'type' => 'Quiz',
            'total_marks' => 10,
            'passing_marks' => 4,
            'status' => 'published',
            'start_at' => now()->subMinutes(5),
            'end_at' => now()->addHour(),
            'created_by' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $first = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assessments/' . $assessment->id . '/start')
            ->assertOk();

        $second = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assessments/' . $assessment->id . '/start')
            ->assertOk();

        $this->assertSame($first->json('data.id'), $second->json('data.id'));
    }

    public function test_teacher_can_manually_evaluate_attempt(): void
    {
        $tenant = $this->createTenant('attempt-eval');
        [$studentUser, $student] = $this->createStudentUser($tenant, 'student-eval@test.local');
        $teacher = $this->createTeacherUser($tenant, 'teacher-eval@test.local');

        Permission::findOrCreate('assessment_attempt.create', 'web');
        Permission::findOrCreate('assessment_attempt.update', 'web');
        Permission::findOrCreate('assessment_attempt.submit', 'web');
        Permission::findOrCreate('submission.evaluate', 'web');

        $studentUser->givePermissionTo([
            'assessment_attempt.create',
            'assessment_attempt.update',
            'assessment_attempt.submit',
        ]);
        $teacher->givePermissionTo(['submission.evaluate']);

        $set = QuestionSet::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Subjective Set',
            'question_type' => 'short_answer',
            'difficulty' => 'medium',
            'total_questions' => 1,
            'status' => 'published',
        ]);

        $question = Question::query()->create([
            'tenant_id' => $tenant->id,
            'question_set_id' => $set->id,
            'stem' => 'Explain gravity.',
            'question_type' => 'short_answer',
            'difficulty' => 'medium',
            'sort_order' => 1,
            'status' => 'active',
        ]);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Science Subjective Test',
            'type' => 'Unit Test',
            'total_marks' => 10,
            'passing_marks' => 4,
            'status' => 'published',
            'start_at' => now()->subMinutes(5),
            'end_at' => now()->addHour(),
            'created_by' => $teacher->id,
        ]);

        $assessment->questions()->create([
            'tenant_id' => $tenant->id,
            'question_id' => $question->id,
            'marks' => 10,
            'sort_order' => 1,
        ]);

        Sanctum::actingAs($studentUser);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assessments/' . $assessment->id . '/start')
            ->assertOk();

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assessments/' . $assessment->id . '/save-answer', [
                'question_id' => $question->id,
                'selected_answer' => ['Gravity pulls objects toward each other.'],
            ])
            ->assertOk();

        $submit = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assessments/' . $assessment->id . '/submit')
            ->assertOk();

        $attemptId = (int) $submit->json('data.id');

        Sanctum::actingAs($teacher);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/assessments/' . $assessment->id . '/attempts/' . $attemptId . '/evaluate', [
                'answers' => [
                    [
                        'question_id' => $question->id,
                        'marks_awarded' => 7,
                        'is_correct' => true,
                    ],
                ],
            ])
            ->assertOk()
                ->assertJsonPath('data.score', 7)
            ->assertJsonPath('data.status', 'evaluated');
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
            'admission_no' => 'ADM-' . $tenant->id,
            'roll_no' => 'R-' . $tenant->id,
            'first_name' => 'Test',
            'last_name' => 'Student',
            'gender' => 'other',
            'date_of_birth' => '2010-01-01',
            'admission_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        return [$user, $student];
    }

    private function createTeacherUser(Tenant $tenant, string $email): User
    {
        return User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Teacher User',
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

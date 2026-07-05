<?php

namespace Tests\Feature\Modules\LiveClass;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\LiveClass\Domain\Models\LiveClassSession;
use Modules\Notification\Domain\Models\TenantNotification;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Domain\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class LiveClassPlatformApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_schedule_live_class_and_calendar_event_is_synced(): void
    {
        $tenant = $this->createTenant('liveclass-a');
        $teacher = $this->createUser($tenant, 'teacher-a@edus.test', 'Teacher A');

        $this->grantPermissions($teacher, ['live_class.create']);

        Sanctum::actingAs($teacher);

        $response = $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/live-classes', [
                'title' => 'Physics Live Session',
                'description' => 'Chapter 1 introduction',
                'host_user_id' => $teacher->id,
                'scheduled_start_at' => now()->addHour()->toIso8601String(),
                'scheduled_end_at' => now()->addHours(2)->toIso8601String(),
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $liveClassId = (int) $response->json('data.id');

        $this->assertDatabaseHas('live_class_sessions', [
            'id' => $liveClassId,
            'tenant_id' => $tenant->id,
            'title' => 'Physics Live Session',
            'status' => 'scheduled',
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'tenant_id' => $tenant->id,
            'source_type' => 'live_class_session',
            'source_id' => $liveClassId,
            'event_type' => 'live_class',
        ]);
    }

    public function test_student_can_join_live_class_and_attendance_is_recorded(): void
    {
        $tenant = $this->createTenant('liveclass-b');
        $host = $this->createUser($tenant, 'teacher-b@edus.test', 'Teacher B');
        $studentUser = $this->createUser($tenant, 'student-b@edus.test', 'Student B');
        $student = $this->createStudent($tenant, $studentUser, 'ADM-B-001');

        $this->grantPermissions($studentUser, ['live_class.join']);

        $session = LiveClassSession::query()->create([
            'tenant_id' => $tenant->id,
            'title' => 'Biology Live Session',
            'description' => 'Cell structure',
            'host_user_id' => $host->id,
            'provider' => 'jitsi',
            'provider_meeting_id' => 'room-bio-1',
            'room_name' => 'room-bio-1',
            'meeting_url' => 'https://meet.jit.si/room-bio-1',
            'scheduled_start_at' => now()->subMinutes(15),
            'scheduled_end_at' => now()->addHour(),
            'actual_start_at' => now()->subMinutes(10),
            'status' => 'live',
        ]);

        Sanctum::actingAs($studentUser);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/live-classes/' . $session->id . '/join')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $session->id);

        $this->assertDatabaseHas('live_class_attendances', [
            'tenant_id' => $tenant->id,
            'live_class_session_id' => $session->id,
            'student_id' => $student->id,
            'attendance_status' => 'joined',
        ]);
    }

    public function test_user_can_view_unread_notifications_and_mark_as_read(): void
    {
        $tenant = $this->createTenant('liveclass-c');
        $user = $this->createUser($tenant, 'notify-c@edus.test', 'Notify C');

        $this->grantPermissions($user, ['notification.view', 'notification.read']);

        $notification = TenantNotification::query()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'notification_type' => 'live_class.started',
            'title' => 'Class Started',
            'body' => 'Chemistry class is now live.',
            'status' => 'sent',
            'channels' => ['in_app'],
            'data' => ['live_class_id' => 5],
            'sent_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->getJson('/api/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.unread_count', 1);

        $this
            ->withHeader('X-Tenant-ID', (string) $tenant->id)
            ->postJson('/api/notifications/' . $notification->id . '/read')
            ->assertOk()
            ->assertJsonPath('data.id', $notification->id)
            ->assertJsonPath('data.status', 'read');
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

    private function createUser(Tenant $tenant, string $email, string $name): User
    {
        return User::query()->create([
            'tenant_id' => $tenant->id,
            'name' => $name,
            'email' => $email,
            'password' => 'password123',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    private function createStudent(Tenant $tenant, User $user, string $admissionNo): Student
    {
        return Student::query()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'admission_no' => $admissionNo,
            'first_name' => 'Student',
            'last_name' => 'User',
            'gender' => 'male',
            'date_of_birth' => '2010-01-01',
            'admission_date' => now()->toDateString(),
            'status' => 'active',
        ]);
    }

    /**
     * @param list<string> $permissions
     */
    private function grantPermissions(User $user, array $permissions): void
    {
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->givePermissionTo($permissions);
    }
}

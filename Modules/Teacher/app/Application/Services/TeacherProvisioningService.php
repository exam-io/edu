<?php

namespace Modules\Teacher\Application\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Teacher\Application\Contracts\TeacherProvisioningServiceInterface;
use Modules\Teacher\Domain\Models\Teacher;

class TeacherProvisioningService implements TeacherProvisioningServiceInterface
{
    public function provisionTeacherUser(Teacher $teacher): void
    {
        if ($teacher->user_id !== null) {
            return;
        }

        $email = $teacher->email ?? sprintf('teacher.%d.%d@placeholder.eduos.local', $teacher->tenant_id, $teacher->id);

        $user = User::query()->create([
            'tenant_id' => $teacher->tenant_id,
            'name' => trim($teacher->first_name . ' ' . $teacher->last_name),
            'email' => $email,
            'password' => Str::password(24),
            'status' => 'active',
        ]);

        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($teacher->tenant_id);
        }

        $user->assignRole('Teacher');

        $teacher->update(['user_id' => $user->id]);
    }
}

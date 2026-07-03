<?php

namespace Modules\Student\Application\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Student\Application\Contracts\StudentProvisioningServiceInterface;
use Modules\Student\Domain\Models\Student;

class StudentProvisioningService implements StudentProvisioningServiceInterface
{
    public function provisionStudentUser(Student $student): void
    {
        if ($student->user_id !== null) {
            return;
        }

        $email = $student->email ?? sprintf('student.%d.%d@placeholder.eduos.local', $student->tenant_id, $student->id);

        $user = User::query()->create([
            'tenant_id' => $student->tenant_id,
            'name' => trim($student->first_name . ' ' . $student->last_name),
            'email' => $email,
            'password' => Str::password(24),
            'status' => 'active',
        ]);

        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($student->tenant_id);
        }

        $user->assignRole('Student');

        $student->update(['user_id' => $user->id]);
    }
}

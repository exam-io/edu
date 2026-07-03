<?php

namespace Modules\Teacher\Application\Contracts;

use Modules\Teacher\Domain\Models\Teacher;

interface TeacherProvisioningServiceInterface
{
    public function provisionTeacherUser(Teacher $teacher): void;
}

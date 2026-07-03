<?php

namespace Modules\Student\Application\Contracts;

use Modules\Student\Domain\Models\Student;

interface StudentProvisioningServiceInterface
{
    public function provisionStudentUser(Student $student): void;
}

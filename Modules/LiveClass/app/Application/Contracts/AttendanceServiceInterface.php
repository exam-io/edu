<?php

namespace Modules\LiveClass\Application\Contracts;

use Modules\LiveClass\Domain\Models\LiveClassAttendance;
use Modules\LiveClass\Domain\Models\LiveClassSession;

interface AttendanceServiceInterface
{
    public function join(LiveClassSession $session): LiveClassAttendance;
}

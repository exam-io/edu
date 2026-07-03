<?php

namespace Modules\Institute\Domain\Enums;

enum AcademicSessionStatus: string
{
    case Planned = 'planned';
    case Active = 'active';
    case Closed = 'closed';
}

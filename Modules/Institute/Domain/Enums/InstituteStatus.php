<?php

namespace Modules\Institute\Domain\Enums;

enum InstituteStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}

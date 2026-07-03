<?php

namespace Modules\Institute\Domain\Enums;

enum InstituteStatus: string
{
    case Provisioning = 'provisioning';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}

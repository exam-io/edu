<?php

namespace Modules\Settings\Application\DTOs;

readonly class TenantThemeData
{
    public function __construct(
        public string $theme,
        public string $language,
        public string $timezone,
        public ?string $primaryColor,
        public ?string $secondaryColor,
    ) {
    }
}

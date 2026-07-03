<?php

namespace Modules\ContentProcessing\Application\DTOs;

readonly class ExtractionResultData
{
    public function __construct(
        public bool $success,
        public ?string $text,
        public ?string $error,
        public array $meta = [],
    ) {}
}

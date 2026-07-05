<?php

namespace Modules\Reporting\Application\DTOs;

readonly class ReportTemplateData
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $source,
        public array $definition,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            name: (string) $input['name'],
            slug: (string) $input['slug'],
            source: (string) $input['source'],
            definition: is_array($input['definition'] ?? null) ? $input['definition'] : [],
        );
    }
}

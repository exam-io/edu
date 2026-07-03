<?php

namespace Modules\AI\Application\Contracts;

interface AIProviderClientInterface
{
    public function generate(string $generationType, string $input, array $options = []): array;
}

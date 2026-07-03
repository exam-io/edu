<?php

namespace Modules\Shared\Application\Contracts;

use Illuminate\Support\Collection;

interface SharedLookupServiceInterface
{
    /** @return list<string> */
    public function supportedTypes(): array;

    /** @return Collection<int, array<string, mixed>> */
    public function list(string $type, ?string $search = null, int $limit = 25): Collection;

    /** @return array<string, mixed> */
    public function find(string $type, string $id): array;
}

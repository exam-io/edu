<?php

namespace Modules\Media\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Media\Application\DTOs\MediaListQueryData;
use Modules\Media\Application\DTOs\MediaMutationData;
use Modules\Media\Domain\Models\MediaAsset;

interface MediaServiceInterface
{
    public function list(MediaListQueryData $query): LengthAwarePaginator;

    public function find(int $id): MediaAsset;

    public function create(MediaMutationData $data): MediaAsset;

    public function update(int $id, MediaMutationData $data): MediaAsset;

    public function delete(int $id): void;
}

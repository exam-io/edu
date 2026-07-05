<?php

namespace Modules\Admissions\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Admissions\Application\DTOs\AdmissionApplicationData;
use Modules\Admissions\Application\DTOs\AdmissionApplicationQueryData;
use Modules\Admissions\Application\DTOs\AdmissionStatusTransitionData;
use Modules\Admissions\Domain\Models\AdmissionApplication;

interface AdmissionApplicationServiceInterface
{
    public function list(AdmissionApplicationQueryData $query): LengthAwarePaginator;

    public function find(int $id): AdmissionApplication;

    public function create(AdmissionApplicationData $data): AdmissionApplication;

    public function changeStatus(int $id, AdmissionStatusTransitionData $data): AdmissionApplication;

    public function delete(int $id): void;
}
